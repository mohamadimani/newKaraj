<?php

namespace App\Http\Requests\Admin;

use App\Enums\User\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateRequest extends FormRequest
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
                'unique:users,mobile,' . $this->teacher->user->id,
            ],
            'gender' => ['nullable', 'string', 'in:' . GenderEnum::implodedValues()],
            'birth_date' => ['nullable', 'date_format:Y-m-d'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'province_id' => ['required', 'exists:provinces,id'],
            'branch_ids' => ['required', 'array'],
            'branch_ids.*' => ['required', 'exists:branches,id'],
            'profession_ids' => ['required', 'array'],
            'profession_ids.*' => ['required', 'exists:professions,id'],
        ];
    }

    public function prepareForValidation()
    {
        // $this->merge([
        // ]);
    }
}
