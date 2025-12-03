<?php

namespace App\Http\Requests\Admin;

use App\Enums\Student\EducationEnum;
use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
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
            'father_name' => ['nullable', 'string', 'max:255'],
            'national_code' => ['nullable', 'string', 'max:255', 'unique:students,national_code,' . $this->student->id.',id'],
            'education' => ['nullable', 'string', 'in:' . EducationEnum::implodedValues()],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'mobile' => [
                'required',
                'numeric',
                'regex:/^09[0-9]{9}$/',
                'unique:users,mobile,' . $this->student->user->id
            ],
            'mobile2' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'province_id' => ['nullable', 'string', 'max:255'],
            'familiarity_way_id' => ['required', 'string', 'max:255'],
            'profession_ids' => ['required', 'array'],
        ];
    }
}
