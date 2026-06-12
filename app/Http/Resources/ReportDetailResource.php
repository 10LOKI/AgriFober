<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'titre'           => $this->titre,
            'type'            => $this->type?->value,
            'status'          => $this->status?->value,
            'resume'          => $this->resume,
            'donnees'         => $this->donnees,
            'generated_at'    => $this->generated_at,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,

            // Program + history have dedicated endpoints; expose lightweight
            // pointers here rather than the full payloads.
            'has_program'     => ! empty($this->programme),
            'histories_count' => $this->whenCounted('histories'),

            'user'            => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'culture'         => $this->whenLoaded('culture', fn() =>
                $this->culture ? new CultureResource($this->culture) : null
            ),
            'parcel'          => $this->whenLoaded('parcel', fn() =>
                $this->parcel ? new ParcelResource($this->parcel) : null
            ),
        ];
    }
}
