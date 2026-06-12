<?php

namespace App\Repositories\Eloquent;

use App\Models\InteractionIA;
use App\Repositories\Contracts\AiConversationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AiConversationRepository implements AiConversationRepositoryInterface
{
    public function __construct(private readonly InteractionIA $model)
    {
    }

    public function contextMessages(string $conversationId, int $userId, int $limit = 10): array
    {
        $exchanges = $this->model
            ->newQuery()
            ->where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse(); // oldest first for the model context

        $messages = [];

        foreach ($exchanges as $exchange) {
            $messages[] = [
                'role'    => 'user',
                'content' => (string) $exchange->prompt_text,
            ];

            $answer = $exchange->response_data['text'] ?? null;

            if (is_string($answer) && $answer !== '') {
                $messages[] = [
                    'role'    => 'assistant',
                    'content' => $answer,
                ];
            }
        }

        return $messages;
    }

    public function log(array $attributes): InteractionIA
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function paginateMessages(string $conversationId, int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->with('parcel:id,nom')
            ->oldest()
            ->oldest('id')
            ->paginate($perPage);
    }

    public function paginateImageAnalyses(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->where('user_id', $userId)
            ->where('input_mode', 'image')
            ->with('parcel:id,nom')
            ->latest()
            ->paginate($perPage);
    }
}
