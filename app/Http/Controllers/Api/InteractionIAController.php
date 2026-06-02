<?php

namespace App\Http\Controllers\Api;

use App\Enums\InputModeEnum;
use App\Enums\InteractionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Http\Requests\AiFeedbackRequest;
use App\Http\Resources\InteractionIAResource;
use App\Models\InteractionIA;
use App\Models\Parcel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InteractionIAController extends Controller
{
    public function chat(AiChatRequest $request): JsonResponse
    {
        $user     = $request->user();
        $message  = $request->input('message');
        $parcelId = $request->input('parcel_id');

        if ($parcelId) {
            $parcel = Parcel::findOrFail($parcelId);
            if ($parcel->user_id !== $user->id && !$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to parcel',
                ], 403);
            }
        }

        $type      = $request->input('type', InteractionTypeEnum::CHAT->value);
        $inputMode = $request->input('input_mode', InputModeEnum::TEXT->value);

        $interaction = InteractionIA::create([
            'user_id'       => $user->id,
            'parcel_id'     => $parcelId,
            'type'          => $type,
            'input_mode'    => $inputMode,
            'prompt_text'   => $message,
            'response_data' => ['text' => 'AI response pending — DeepSeek integration in progress.'],
            'tokens_used'   => 0,
            'engine'        => 'default',
        ]);

        return response()->json([
            'success' => true,
            'data'    => new InteractionIAResource($interaction),
        ], 201);
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
