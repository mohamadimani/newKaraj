<?php

namespace App\Http\Requests\Admin\MarketingSms;

use Illuminate\Foundation\Http\FormRequest;

class MarketingSmsItemUpdateRequest extends FormRequest
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
            'content' => ['required', 'string', 'min:3', 'max:1000'],
            'is_active' => ['required', 'boolean'],
            'show_student_name' => ['required', 'boolean'],
            'show_secretary_name' => ['required', 'boolean'],
            'show_branch_name' => ['required', 'boolean'],
            'discount_amount' => ['sometimes', 'numeric', 'min:0'],
            'after_time_seconds' => ['required', 'integer', 'min:0'],
            'after_time_minutes' => ['required', 'integer', 'min:0'],
            'after_time_hours' => ['required', 'integer', 'min:0'],
            'after_time_days' => ['required', 'integer', 'min:0'],
            'after_time_months' => ['required', 'integer', 'min:0'],
            'after_time_years' => ['required', 'integer', 'min:0'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->is_active === 'on' ? true : false,
            'show_student_name' => $this->show_student_name === 'on' ? true : false,
            'show_secretary_name' => $this->show_secretary_name === 'on' ? true : false,
            'show_branch_name' => $this->show_branch_name === 'on' ? true : false,
            'discount_amount' => floatval(str_replace(',', '', $this->discount_amount ?? '0')),
        ]);
    }
}
