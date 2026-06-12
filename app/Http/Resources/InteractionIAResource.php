<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionIAResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'conversation_id' => $this->conversation_id,
            'type'           => $this->type?->value,
            'input_mode'     => $this->input_mode?->value,
            'prompt_text'    => $this->prompt_text,
            'response_data'  => $this->response_data,
            'tokens_used'    => $this->tokens_used,
            'engine'         => $this->engine,
            'model_version'  => $this->model_version,
            'feedback_rating' => $this->feedback_rating,
            'created_at'     => $this->created_at,
            'parcel'         => $this->whenLoaded('parcel', fn() =>
                $this->parcel ? ['id' => $this->parcel->id, 'nom' => $this->parcel->nom] : null
            ),
        ];
    }
}
