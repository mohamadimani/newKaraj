<?php

namespace App\Http\Requests\Admin;

use App\Enums\User\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;

class SecretaryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:30'],
            'last_name' => ['required', 'string', 'min:3', 'max:30'],
            'mobile' => [
                'required',
                'numeric',
                'regex:/^09[0-9]{9}$/',
                // 'unique:users,mobile'
            ],
            'gender' => ['required', 'string', 'in:' . GenderEnum::implodedValues()],
            'is_active' => ['required', 'boolean'],
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'phones' => ['required', 'array', 'exists:phones,id'],
            // 'internal_phone' => 'required|numeric|unique:phone_internals,number,NULL,id,deleted_at,NULL',
            'province_id' => ['required', 'exists:provinces,id'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->is_active === 'on' ? true : false,
        ]);
    }
}
