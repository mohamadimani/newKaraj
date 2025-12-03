<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfessionStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:professions,title,NULL,id,deleted_at,NULL',
            'public_price' => 'required|numeric|min:0',
            'public_duration_hours' => 'required|numeric|min:0',
            'public_capacity' => 'required|numeric|min:0',
            'private_price' => 'required|numeric|min:0',
            'private_duration_hours' => 'required|numeric|min:0',
            'private_capacity' => 'required|numeric|min:0',
            'branch_ids' => 'required|array|exists:branches,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'public_price' => str_replace(',', '', $this->public_price),
            'private_price' => str_replace(',', '', $this->private_price),
        ]);
    }
}
