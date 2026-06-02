<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'nom'                   => $this->nom,
            'surface'               => $this->surface,
            'status'                => $this->status?->value,
            'health_score'          => $this->health_score,
            'date_plantation'       => $this->date_plantation,
            'date_recolte_estimee'  => $this->date_recolte_estimee,
            'latitude'              => $this->latitude,
            'longitude'             => $this->longitude,
            'created_at'            => $this->created_at,
            'weather_data_count'    => $this->whenCounted('weatherData'),
            'culture'               => $this->whenLoaded('culture', fn() =>
                new CultureResource($this->culture)
            ),
            'interaction_ias_count' => $this->when(
                $this->relationLoaded('interactionIas'),
                fn() => $this->interactionIas->count()
            ),
        ];
    }
}
