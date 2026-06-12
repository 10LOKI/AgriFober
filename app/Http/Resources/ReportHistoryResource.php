<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'action'      => $this->action,
            'changements' => $this->changements,
            'note'        => $this->note,
            'created_at'  => $this->created_at,
            'user'        => $this->whenLoaded('user', fn() =>
                $this->user ? [
                    'id'   => $this->user->id,
                    'name' => $this->user->name,
                ] : null
            ),
        ];
    }
}
