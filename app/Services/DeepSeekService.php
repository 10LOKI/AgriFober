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
