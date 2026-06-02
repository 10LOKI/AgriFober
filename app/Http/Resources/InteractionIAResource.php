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
            'type'           => $this->type?->value,
            'input_mode'     => $this->input_mode?->value,
            'prompt_text'    => $this->prompt_text,
            'response_data'  => $this->response_data,
            'feedback_rating' => $this->feedback_rating,
            'created_at'     => $this->created_at,
            'parcel'         => $this->whenLoaded('parcel', fn() =>
                $this->parcel ? ['id' => $this->parcel->id, 'nom' => $this->parcel->nom] : null
            ),
        ];
    }
}
