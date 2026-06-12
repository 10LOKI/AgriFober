<?php

namespace App\Repositories\Contracts;

use App\Models\InteractionIA;

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
}
