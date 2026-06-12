<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'report_id'    => $this->id,
            'titre'        => $this->titre,
            'type'         => $this->type?->value,
            'status'       => $this->status?->value,
            'generated_at' => $this->generated_at,
            'has_program'  => ! empty($this->programme),
            'program'      => $this->programme,
            'culture'      => $this->whenLoaded('culture', fn() =>
                $this->culture ? [
                    'id'         => $this->culture->id,
                    'nom_commun' => $this->culture->nom_commun,
                    'type'       => $this->culture->type?->value,
                ] : null
            ),
            'parcel'       => $this->whenLoaded('parcel', fn() =>
                $this->parcel ? [
                    'id'      => $this->parcel->id,
                    'nom'     => $this->parcel->nom,
                    'surface' => $this->parcel->surface,
                ] : null
            ),
        ];
    }
}
