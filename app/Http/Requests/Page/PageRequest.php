<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'is_active' => 'required',
            'published_at' => 'nullable|date',
        ];

        if ($this->method() === 'POST') {
            $rules['content_type_id'] = 'required';
        }

        return $rules;
    }
}
