<?php

namespace App\Services;

use App\Services\Exceptions\DeepSeekException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around the DeepSeek (OpenAI-compatible) chat completions API.
 *
 * Configuration is pulled from config/services.php ('deepseek'); no secrets
 * or model names are hard-coded here.
 */
class DeepSeekService
{
    private string $baseUrl;
    private string $apiKey;
    private string $model;
    private int $timeout;
    private int $retries;

    public function __construct()
    {
        $config = config('services.deepseek');

        $this->apiKey  = (string) ($config['key'] ?? '');
        $this->baseUrl = rtrim((string) ($config['base_url'] ?? 'https://api.deepseek.com'), '/');
        $this->model   = (string) ($config['model'] ?? 'deepseek-chat');
        $this->timeout = (int) ($config['timeout'] ?? 30);
        $this->retries = (int) ($config['retries'] ?? 2);
    }

    /**
     * Whether the integration has the minimum configuration to run.
     */
    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Send a chat completion request.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array<string, mixed>  $options  Overrides: model, temperature, max_tokens, response_format, ...
     * @return array{content: string, role: string, model: string, usage: array<string, mixed>, finish_reason: ?string, raw: array<string, mixed>}
     *
     * @throws DeepSeekException
     */
    public function chatCompletion(array $messages, array $options = []): array
    {
        if (! $this->isConfigured()) {
            throw DeepSeekException::missingApiKey();
        }

        $payload = array_merge([
            'model'       => $this->model,
            'messages'    => $messages,
            'temperature' => 0.7,
            'stream'      => false,
        ], $options);

        try {
            $response = $this->client()->post('/chat/completions', $payload);
        } catch (ConnectionException $e) {
            Log::error('DeepSeek connection failure', [
                'message' => $e->getMessage(),
                'model'   => $payload['model'],
            ]);

            throw DeepSeekException::fromConnection($e);
        }

        if ($response->failed()) {
            Log::error('DeepSeek API error response', [
                'status' => $response->status(),
                'body'   => $response->json() ?? $response->body(),
                'model'  => $payload['model'],
            ]);

            throw DeepSeekException::fromResponse($response->status(), $response->json() ?? $response->body());
        }

        return $this->normalize($response->json());
    }

    /**
     * Produce a structured, analytical explanation of an agricultural subject
     * (diagnostic, report anomaly, metric...). A dedicated system prompt forces
     * DeepSeek to answer as a single JSON object with a fixed schema.
     *
     * @param  string  $subject  Free-text description of what to explain.
     * @param  array<string, mixed>  $data  Optional structured context (metrics, anomaly payload).
     * @return array{structured: array<string, mixed>|null, content: string, model: string, usage: array<string, mixed>, raw: array<string, mixed>}
     *
     * @throws DeepSeekException
     */
    public function explain(string $subject, array $data = []): array
    {
        $userContent = $subject;

        if ($data !== []) {
            $userContent .= "\n\nDonnées à analyser:\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        $messages = [
            ['role' => 'system', 'content' => $this->explanationSystemPrompt()],
            ['role' => 'user', 'content' => $userContent],
        ];

        $result = $this->chatCompletion($messages, [
            'temperature'     => 0.3,
            'response_format' => ['type' => 'json_object'],
        ]);

        $structured = json_decode($result['content'], true);

        return [
            'structured' => is_array($structured) ? $structured : null,
            'content'    => $result['content'],
            'model'      => $result['model'],
            'usage'      => $result['usage'],
            'raw'        => $result['raw'],
        ];
    }

    /**
     * Analyze a crop/field image and return a structured agronomic diagnosis.
     * Uses the OpenAI-compatible multimodal message format; the image is passed
     * as a base64 data URI so no public URL is required.
     *
     * @param  string  $prompt  Optional user question/context.
     * @param  string  $imageDataUri  data:<mime>;base64,<...> URI of the image.
     * @return array{structured: array<string, mixed>|null, content: string, model: string, usage: array<string, mixed>, raw: array<string, mixed>}
     *
     * @throws DeepSeekException
     */
    public function analyzeImage(string $prompt, string $imageDataUri): array
    {
        $messages = [
            ['role' => 'system', 'content' => $this->visionSystemPrompt()],
            ['role' => 'user', 'content' => [
                ['type' => 'text', 'text' => $prompt !== '' ? $prompt : 'Analyse cette image agricole.'],
                ['type' => 'image_url', 'image_url' => ['url' => $imageDataUri]],
            ]],
        ];

        $result = $this->chatCompletion($messages, [
            'model'           => (string) (config('services.deepseek.vision_model') ?: $this->model),
            'temperature'     => 0.3,
            'response_format' => ['type' => 'json_object'],
        ]);

        $structured = json_decode($result['content'], true);

        return [
            'structured' => is_array($structured) ? $structured : null,
            'content'    => $result['content'],
            'model'      => $result['model'],
            'usage'      => $result['usage'],
            'raw'        => $result['raw'],
        ];
    }

    /**
     * Vision system prompt: agronomic image diagnosis, strict JSON schema.
     */
    private function visionSystemPrompt(): string
    {
        return 'Tu es Agriforb, un agronome qui diagnostique des images de '
            . 'cultures et de parcelles. Analyse l\'image et réponds UNIQUEMENT '
            . 'avec un objet JSON valide respectant ce schéma: {'
            . '"diagnostic": string, '
            . '"etat_general": "bon"|"moyen"|"critique", '
            . '"problemes_detectes": string[], '
            . '"recommandations": string[], '
            . '"niveau_confiance": "faible"|"moyen"|"eleve"}. '
            . 'Rédige les valeurs en français.';
    }

    /**
     * Analytical system prompt: pins the assistant to a strict JSON schema so
     * the breakdown is machine-parseable for the frontend.
     */
    private function explanationSystemPrompt(): string
    {
        return 'Tu es Agriforb, un agronome analyste. Analyse le sujet fourni et '
            . 'réponds UNIQUEMENT avec un objet JSON valide, sans texte autour, '
            . 'respectant exactement ce schéma: {'
            . '"resume": string, '
            . '"analyse": string, '
            . '"causes_probables": string[], '
            . '"recommandations": string[], '
            . '"niveau_confiance": "faible"|"moyen"|"eleve"}. '
            . 'Rédige les valeurs en français, de façon claire et actionnable.';
    }

    /**
     * Build the pre-configured HTTP client (auth, base URL, timeout, retry).
     */
    private function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->asJson()
            ->timeout($this->timeout)
            ->retry($this->retries, 200, throw: false);
    }

    /**
     * Flatten the OpenAI-compatible payload to the fields callers actually need.
     *
     * @param  array<string, mixed>|null  $body
     * @return array{content: string, role: string, model: string, usage: array<string, mixed>, finish_reason: ?string, raw: array<string, mixed>}
     */
    private function normalize(?array $body): array
    {
        $body    = $body ?? [];
        $choice  = $body['choices'][0] ?? [];
        $message = $choice['message'] ?? [];

        return [
            'content'       => (string) ($message['content'] ?? ''),
            'role'          => (string) ($message['role'] ?? 'assistant'),
            'model'         => (string) ($body['model'] ?? $this->model),
            'usage'         => $body['usage'] ?? [],
            'finish_reason' => $choice['finish_reason'] ?? null,
            'raw'           => $body,
        ];
    }
}
