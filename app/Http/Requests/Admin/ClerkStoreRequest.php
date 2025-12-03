<?php

namespace App\Http\Requests\Admin;

use App\Enums\User\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;

class ClerkStoreRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:3', 'max:30'],
            'last_name' => ['required', 'string', 'min:3', 'max:30'],
            'mobile' => [
                'required',
                'numeric',
                'regex:/((0?9)|(\+?989))\d{2}\W?\d{3}\W?\d{4}/i',
                // 'unique:users,mobile'
            ],
            'gender' => ['required', 'string', 'in:' . GenderEnum::implodedValues()],
            'is_active' => ['required', 'boolean'],
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'province_id' => ['required', 'exists:provinces,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->is_active === 'on' ? true : false,
        ]);
    }
}
