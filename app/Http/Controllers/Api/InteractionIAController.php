<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InteractionIA;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionIAController extends Controller
{
    /**
     * Chat with AI (simple text).
     * POST /api/ai/chat
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'min:3', 'max:2000'],
            'parcel_id' => ['nullable', 'exists:parcels,id'],
        ]);

        $user = $request->user();
        $message = $request->input('message');
        $parcelId = $request->input('parcel_id');

        // Check parcel ownership if provided
        if ($parcelId) {
            $parcel = \App\Models\Parcel::findOrFail($parcelId);
            if ($parcel->user_id !== $user->id && !$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to parcel'
                ], 403);
            }
        }

        // Log the interaction (response will be added later with DeepSeek integration)
        $interaction = InteractionIA::create([
            'user_id' => $user->id,
            'parcel_id' => $parcelId,
            'question' => $message,
            'response' => 'AI response will be available soon. DeepSeek API integration pending.',
            'type' => 'text',
            'input_mode' => 'text',
            'tokens_used' => 0,
            'response_time_ms' => 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'question' => $message,
                'response' => $interaction->response,
                'created_at' => $interaction->created_at,
                'note' => 'AI integration in progress'
            ]
        ]);
    }

    /**
     * Get user's AI interaction history.
     * GET /api/ai/history
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();
        $history = $user->interactionIas()
            ->with('parcel:id,nom')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Delete a specific interaction.
     * DELETE /api/ai/history/{id}
     */
    public function destroy(string $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $interaction = InteractionIA::findOrFail($id);

        // Only owner can delete
        if ($interaction->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $interaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Interaction deleted successfully'
        ]);
    }
}
