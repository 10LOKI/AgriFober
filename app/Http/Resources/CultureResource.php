<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CultureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nom_commun'     => $this->nom_commun,
            'nom_scientifique' => $this->nom_scientifique,
            'type'           => $this->type?->value,
            'saison'         => $this->saison?->value,
            'region'         => $this->region,
            'soil_type'      => $this->soil_type?->value,
            'ph_sol_min'     => $this->ph_sol_min,
            'ph_sol_max'     => $this->ph_sol_max,
            'temp_min'       => $this->temp_min,
            'temp_max'       => $this->temp_max,
            'besoin_eau_cycle' => $this->besoin_eau_cycle,
            'conseils'       => $this->conseils,
            'products'       => $this->whenLoaded('products', fn() =>
                ProductResource::collection($this->products)
            ),
        ];
    }
}
