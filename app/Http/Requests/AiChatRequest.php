<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message'         => ['required', 'string', 'min:3', 'max:2000'],
            'conversation_id' => ['nullable', 'uuid'],
            'parcel_id'       => ['nullable', 'integer', 'exists:parcels,id'],
            'type'            => ['sometimes', 'in:chat,diagnostic,recommandation'],
            'input_mode'      => ['sometimes', 'in:text,voice,image'],
        ];
    }
}
