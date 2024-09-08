<?php

namespace App\Http\Requests\ContentType;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_content' => 'required|array',
            'item_content.*.content_type_field_id' => 'required|integer|exists:content_type_fields,id',
            'item_content.*.value' => 'nullable',
            'localeLanguage' => 'required|integer|exists:languages,id',
        ];
    }
}
