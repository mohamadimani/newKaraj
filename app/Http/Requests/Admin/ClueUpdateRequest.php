<?php

namespace App\Http\Requests\Admin;

use App\Enums\User\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;

class ClueUpdateRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => ['required', 'string', 'in:' . GenderEnum::implodedValues()],
            'profession_ids' => 'required|array',
            'profession_ids.*' => 'required|exists:professions,id',
            'province_id' => 'required|exists:provinces,id',
            'familiarity_way_id' => 'required|exists:familiarity_ways,id',
            'branch_id' => 'required|exists:branches,id',
        ];
    }
}
