<?php

namespace App\Http\Requests\Admin;

use App\Enums\User\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;

class ClueStoreRequest extends FormRequest
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
        $validate = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'foreign' => ['required', 'string', 'in:no,yes'],
            'gender' => ['required', 'string', 'in:' . GenderEnum::implodedValues()],
            'profession_ids' => 'required|array',
            'profession_ids.*' => 'required|exists:professions,id',
            'province_id' => 'required|exists:provinces,id',
            'familiarity_way_id' => 'required|exists:familiarity_ways,id',
            'phone_internal_id' => 'required|exists:phone_internals,id',
            'redirect_to_course_register' => 'boolean',
        ];

        if ($this->foreign == 'yes') {
            $validate['mobile'] = ['required', 'numeric', 'unique:users,mobile'];
        } else {
            $validate['mobile'] = ['required', 'numeric', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'];
        }

        return $validate;
    }
}
