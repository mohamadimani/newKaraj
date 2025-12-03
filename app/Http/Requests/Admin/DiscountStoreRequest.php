<?php

namespace App\Http\Requests\Admin;

use App\Enums\Discount\AmountTypeEnum;
use App\Enums\Discount\DiscountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'amount_type' => ['required', 'string', 'in:' . AmountTypeEnum::implodedValues()],
            'discount_type' => ['required', 'string', 'in:' . DiscountTypeEnum::implodedValues()],
            'profession_id' => ['nullable', 'required_if:discount_type,' . DiscountTypeEnum::PROFESSION->value, 'exists:professions,id'],
            'user_id' => ['nullable', 'required_if:discount_type,' . DiscountTypeEnum::USER->value, 'exists:users,id'],
            'usage_limit' => ['required', 'numeric', 'min:1'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:1'],
            'is_online' => ['boolean'],
            'course_id' => ['nullable', 'required_if:discount_type,' . DiscountTypeEnum::COURSE->value, 'exists:online_courses,id'],
            'available_from' => ['required', 'date'],
            'available_until' => ['required', 'date', 'after:available_from'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'minimum_order_amount' => floatval(str_replace(',', '', $this->minimum_order_amount)),
        ]);
    }
}
