<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GroupDescriptionUpdateRequest extends FormRequest
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
            'profession_ids' => ['required', 'array', 'exists:professions,id'],
            'description' => ['required', 'string'],
            'sort' => ['sometimes', 'array'],
            'sort.*' => ['required', 'integer'],
        ];
    }
}
