<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiExplainRequest extends FormRequest
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
            'subject'         => ['required', 'string', 'min:3', 'max:2000'],
            'data'            => ['nullable', 'array'],
            'conversation_id' => ['nullable', 'uuid'],
            'parcel_id'       => ['nullable', 'integer', 'exists:parcels,id'],
        ];
    }
}
