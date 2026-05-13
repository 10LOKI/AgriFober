<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ParcelStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization is handled by controller using policies
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['nullable', 'string', 'max:255'],
            'culture_id' => ['nullable', 'exists:cultures,id'],
            'surface' => ['required', 'numeric', 'min:0.01'],
            'date_plantation' => ['nullable', 'date'],
            'date_recolte_estimee' => ['nullable', 'date', 'after_or_equal:date_plantation'],
            'status' => ['sometimes', 'in:grow,harvest,fallow'],
            'health_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
