<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'nom_commercial'      => $this->nom_commercial,
            'type'                => $this->type?->value,
            'description'         => $this->description,
            'composant_actif'     => $this->composant_actif,
            'dosage_recommande'   => $this->dosage_recommande,
            'delai_avant_recolte' => $this->delai_avant_recolte,
            'safety_instructions' => $this->safety_instructions,
            'dosage_specifique'   => $this->pivot?->dosage_specifique ?? null,
            'notes'               => $this->pivot?->notes ?? null,
        ];
    }
}
