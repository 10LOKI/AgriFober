<?php

namespace App\Http\Controllers\Api;

use App\Enums\InputModeEnum;
use App\Enums\InteractionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Http\Requests\AiExplainRequest;
use App\Http\Requests\AiFeedbackRequest;
use App\Http\Requests\AiImageRequest;
use App\Http\Resources\InteractionIACollection;
use App\Http\Resources\InteractionIAResource;
use App\Models\InteractionIA;
use App\Models\Parcel;
use App\Repositories\Contracts\AiConversationRepositoryInterface;
use App\Services\DeepSeekService;
use App\Services\Exceptions\DeepSeekException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InteractionIAController extends Controller
{
    public function __construct(
        private readonly DeepSeekService $deepseek,
        private readonly AiConversationRepositoryInterface $conversations,
    ) {
    }

    /**
     * POST /api/ai/chat
     * Main chatbot turn: validate, replay conversation context, call DeepSeek,
     * persist the exchange, and return the structured reply.
     */
    public function chat(AiChatRequest $request): JsonResponse
    {
        $user      = $request->user();
        $message   = $request->input('message');
        $parcelId  = $request->input('parcel_id');
        $type      = $request->input('type', InteractionTypeEnum::CHAT->value);
        $inputMode = $request->input('input_mode', InputModeEnum::TEXT->value);

        if ($parcelId) {
            $parcel = Parcel::findOrFail($parcelId);
            if ($parcel->user_id !== $user->id && ! $user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to parcel',
                ], 403);
            }
        }

        // Resume an existing thread or open a new one.
        $conversationId = $request->input('conversation_id') ?? (string) Str::uuid();

        $history = $request->filled('conversation_id')
            ? $this->conversations->contextMessages($conversationId, $user->id)
            : [];

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->systemPrompt()]],
            $history,
            [['role' => 'user', 'content' => $message]],
        );

        try {
            $result = $this->deepseek->chatCompletion($messages);
        } catch (DeepSeekException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The AI assistant is temporarily unavailable. Please try again shortly.',
            ], 503);
        }

        $interaction = $this->conversations->log([
            'user_id'         => $user->id,
            'parcel_id'       => $parcelId,
            'conversation_id' => $conversationId,
            'type'            => $type,
            'input_mode'      => $inputMode,
            'prompt_text'     => $message,
            'response_data'   => [
                'text'  => $result['content'],
                'usage' => $result['usage'],
            ],
            'tokens_used'     => (int) ($result['usage']['total_tokens'] ?? 0),
            'engine'          => 'deepseek',
            'model_version'   => $result['model'],
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'conversation_id' => $conversationId,
                'reply'           => $result['content'],
                'interaction'     => new InteractionIAResource($interaction),
            ],
        ], 201);
    }

    /**
     * POST /api/ai/explain
     * Structured analytical breakdown of a diagnostic / report anomaly. The
     * dedicated analytical prompt + JSON schema live inside DeepSeekService.
     */
    public function explain(AiExplainRequest $request): JsonResponse
    {
        $user      = $request->user();
        $subject   = $request->input('subject');
        $data      = $request->input('data', []);
        $parcelId  = $request->input('parcel_id');

        if ($parcelId) {
            $parcel = Parcel::findOrFail($parcelId);
            if ($parcel->user_id !== $user->id && ! $user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to parcel',
                ], 403);
            }
        }

        $conversationId = $request->input('conversation_id') ?? (string) Str::uuid();

        try {
            $result = $this->deepseek->explain($subject, is_array($data) ? $data : []);
        } catch (DeepSeekException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The AI assistant is temporarily unavailable. Please try again shortly.',
            ], 503);
        }

        $interaction = $this->conversations->log([
            'user_id'         => $user->id,
            'parcel_id'       => $parcelId,
            'conversation_id' => $conversationId,
            'type'            => InteractionTypeEnum::DIAGNOSTIC->value,
            'input_mode'      => InputModeEnum::TEXT->value,
            'prompt_text'     => $subject,
            'response_data'   => [
                'text'       => $result['content'],
                'structured' => $result['structured'],
                'usage'      => $result['usage'],
            ],
            'tokens_used'     => (int) ($result['usage']['total_tokens'] ?? 0),
            'engine'          => 'deepseek',
            'model_version'   => $result['model'],
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'conversation_id' => $conversationId,
                'explanation'     => $result['structured'],
                'raw'             => $result['structured'] === null ? $result['content'] : null,
                'interaction'     => new InteractionIAResource($interaction),
            ],
        ], 201);
    }

    /**
     * POST /api/ai/analyze-image
     * Upload a crop/field image, run a DeepSeek vision diagnosis, store the
     * file + structured result, and return the analysis.
     */
    public function analyzeImage(AiImageRequest $request): JsonResponse
    {
        $user     = $request->user();
        $message  = (string) $request->input('message', '');
        $parcelId = $request->input('parcel_id');

        if ($parcelId) {
            $parcel = Parcel::findOrFail($parcelId);
            if ($parcel->user_id !== $user->id && ! $user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to parcel',
                ], 403);
            }
        }

        $file     = $request->file('image');
        $dataUri  = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));

        try {
            $result = $this->deepseek->analyzeImage($message, $dataUri);
        } catch (DeepSeekException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The AI assistant is temporarily unavailable. Please try again shortly.',
            ], 503);
        }

        // Persist the upload only once the analysis succeeds.
        $imagePath = $file->store('ai-images', 'public');

        $conversationId = $request->input('conversation_id') ?? (string) Str::uuid();

        $interaction = $this->conversations->log([
            'user_id'         => $user->id,
            'parcel_id'       => $parcelId,
            'conversation_id' => $conversationId,
            'type'            => InteractionTypeEnum::ANALYSE_IMAGE->value,
            'input_mode'      => InputModeEnum::IMAGE->value,
            'prompt_text'     => $message !== '' ? $message : 'Analyse image',
            'image_path'      => $imagePath,
            'response_data'   => [
                'text'       => $result['content'],
                'structured' => $result['structured'],
                'usage'      => $result['usage'],
            ],
            'tokens_used'     => (int) ($result['usage']['total_tokens'] ?? 0),
            'engine'          => 'deepseek',
            'model_version'   => $result['model'],
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'conversation_id' => $conversationId,
                'analysis'        => $result['structured'],
                'raw'             => $result['structured'] === null ? $result['content'] : null,
                'interaction'     => new InteractionIAResource($interaction),
            ],
        ], 201);
    }

    /**
     * GET /api/ai/image-analyses
     * Paginated list of the user's past image/vision analyses.
     */
    public function imageAnalyses(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => ['sometimes', 'integer', 'min:5', 'max:100'],
        ]);

        $analyses = $this->conversations->paginateImageAnalyses(
            userId: $request->user()->id,
            perPage: (int) ($validated['per_page'] ?? 20),
        );

        return (new InteractionIACollection($analyses))
            ->additional(['success' => true])
            ->response();
    }

    /**
     * GET /api/ai/conversations/{conversation}
     * Paginated log of a single conversation (oldest first) so the frontend
     * can reload a previous chat session. Owner-scoped.
     */
    public function conversationLog(Request $request, string $conversation): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => ['sometimes', 'integer', 'min:5', 'max:100'],
        ]);

        $messages = $this->conversations->paginateMessages(
            conversationId: $conversation,
            userId: $request->user()->id,
            perPage: (int) ($validated['per_page'] ?? 20),
        );

        // No owned exchanges under this id — treat as a missing conversation.
        if ($messages->total() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation not found.',
            ], 404);
        }

        return (new InteractionIACollection($messages))
            ->additional([
                'success'         => true,
                'conversation_id' => $conversation,
            ])
            ->response();
    }

    /**
     * Base instruction steering the assistant toward the agricultural domain.
     */
    private function systemPrompt(): string
    {
        return 'Tu es Agriforb, un assistant agricole expert. Réponds de manière '
            . 'claire, pratique et concise, en français, en t\'appuyant sur les '
            . 'bonnes pratiques agronomiques. Si une information manque, demande '
            . 'des précisions plutôt que d\'inventer.';
    }

    public function history(Request $request): JsonResponse
    {
        $user    = $request->user();
        $history = $user->interactionIas()
            ->with('parcel:id,nom')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => InteractionIAResource::collection($history),
            'meta'    => [
                'current_page' => $history->currentPage(),
                'last_page'    => $history->lastPage(),
                'per_page'     => $history->perPage(),
                'total'        => $history->total(),
            ],
        ]);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $user        = $request->user();
        $interaction = InteractionIA::findOrFail($id);

        if ($interaction->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $interaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Interaction deleted successfully',
        ]);
    }

    public function feedback(string $id, AiFeedbackRequest $request): JsonResponse
    {
        $user        = $request->user();
        $interaction = InteractionIA::findOrFail($id);

        if ($interaction->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $interaction->update(['feedback_rating' => $request->validated()['rating']]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback recorded',
            'data'    => new InteractionIAResource($interaction->fresh()),
        ]);
    }
}
