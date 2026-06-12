<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'titre'        => $this->titre,
            'type'         => $this->type?->value,
            'status'       => $this->status?->value,
            'resume'       => $this->resume,
            'generated_at' => $this->generated_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'user'         => $this->whenLoaded('user', fn() => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
            'parcel'       => $this->whenLoaded('parcel', fn() => [
                'id'      => $this->parcel->id,
                'nom'     => $this->parcel->nom,
                'surface' => $this->parcel->surface,
            ]),
            'culture'      => $this->whenLoaded('culture', fn() => [
                'id'         => $this->culture->id,
                'nom_commun' => $this->culture->nom_commun,
                'type'       => $this->culture->type?->value,
            ]),
        ];
    }
}
