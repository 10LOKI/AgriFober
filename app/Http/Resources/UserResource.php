<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'username'         => $this->username,
            'name'             => $this->name,
            'email'            => $this->email,
            'role'             => $this->role?->value,
            'region'           => $this->region,
            'experience_level' => $this->experience_level?->value,
            'surface_totale'   => $this->surface_totale,
            'is_approved'      => $this->is_approved,
            'created_at'       => $this->created_at,
        ];
    }
}
