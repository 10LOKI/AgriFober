<?php

namespace App\Repositories\Contracts;

use App\Models\InteractionIA;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AiConversationRepositoryInterface
{
    /**
     * Rebuild the OpenAI-style message history for a conversation, scoped to
     * its owner, oldest first. Each stored exchange yields a user message and
     * the assistant reply.
     *
     * @return array<int, array{role: string, content: string}>
     */
    public function contextMessages(string $conversationId, int $userId, int $limit = 10): array;

    /**
     * Persist a single AI exchange (prompt + structured response).
     *
     * @param  array<string, mixed>  $attributes
     */
    public function log(array $attributes): InteractionIA;

    /**
     * Paginate every exchange of a conversation owned by the user, oldest
     * first, so the frontend can replay a previous chat session.
     */
    public function paginateMessages(string $conversationId, int $userId, int $perPage = 20): LengthAwarePaginator;

    /**
     * Paginate a user's past image/vision analyses (input_mode = image),
     * newest first.
     */
    public function paginateImageAnalyses(int $userId, int $perPage = 20): LengthAwarePaginator;
}
