<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'nom_commercial'     => $this->nom_commercial,
            'description'        => $this->description,
            'composant_actif'    => $this->composant_actif,
            'dosage_recommande'  => $this->dosage_recommande,
            'delai_avant_recolte' => $this->delai_avant_recolte,
            'type'               => $this->type?->value,
            'avantages'          => $this->avantages,
            'usage_method'       => $this->usage_method,
            'safety_instructions' => $this->safety_instructions,
            'image'              => $this->image,
            'pivot'              => $this->when(
                $this->relationLoaded('pivot') || isset($this->pivot),
                fn() => [
                    'dosage_specifique' => $this->pivot?->dosage_specifique ?? null,
                    'notes'             => $this->pivot?->notes ?? null,
                ]
            ),
            'cultures'           => $this->whenLoaded('cultures', fn() =>
                CultureResource::collection($this->cultures)
            ),
        ];
    }
}
