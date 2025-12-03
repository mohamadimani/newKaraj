<?php

namespace App\Http\Requests\Admin;

use App\Models\Profession;
use Illuminate\Foundation\Http\FormRequest;

class CourseReserveStoreRequest extends FormRequest
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
        $profession = Profession::find($this->profession_id);

        return [
            'clue_id' => ['required', 'exists:clues,id'],
            'profession_id' => ['required', 'exists:professions,id'],
            'secretary_id' => ['required', 'exists:secretaries,id'],
            'course_reserve_description' => ['nullable', 'string'],
            'paid_amount' => ['required', 'numeric', 'min:500000', 'lte:' . $profession?->public_price],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'pay_date' => ['required'],
            'payment_description' => ['nullable', 'string'],
            'paid_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2000',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'paid_amount' => floatval(str_replace(',', '', $this->paid_amount)),
        ]);
    }
}
