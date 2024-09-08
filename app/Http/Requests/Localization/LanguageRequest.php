<?php

namespace App\Http\Requests\Localization;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'iso_code' => 'required|string|max:2|unique:languages,iso_code'. (
                $this->route('languageId') ? ',' . $this->route('languageId') : ''
            ),
            'name' => 'required|string|max:255',
            'is_rtl' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ];
    }
}
