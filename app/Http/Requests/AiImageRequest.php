<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiImageRequest extends FormRequest
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
            'image'           => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5 MB
            'message'         => ['nullable', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'uuid'],
            'parcel_id'       => ['nullable', 'integer', 'exists:parcels,id'],
        ];
    }
}
