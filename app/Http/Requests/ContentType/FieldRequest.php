<?php

namespace App\Http\Requests\ContentType;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'validation' => 'nullable|array',
            'placeholder' => 'nullable|string|max:255',
            'default_value' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_required' => 'nullable|boolean',
            'is_unique' => 'nullable|boolean',
            'is_searchable' => 'nullable|boolean',
        ];
    }
}
