<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom_commercial'      => ['required', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'composant_actif'     => ['nullable', 'string', 'max:255'],
            'dosage_recommande'   => ['nullable', 'string', 'max:255'],
            'delai_avant_recolte' => ['nullable', 'integer', 'min:0'],
            'type'                => ['required', 'in:engrais,pesticide,fongicide,herbicide,biologique'],
            'avantages'           => ['nullable', 'string'],
            'usage_method'        => ['nullable', 'string'],
            'safety_instructions' => ['nullable', 'string'],
            'image'               => ['nullable', 'string', 'max:2048'],
            'culture_ids'         => ['nullable', 'array'],
            'culture_ids.*'       => ['integer', 'exists:cultures,id'],
        ];
    }
}
