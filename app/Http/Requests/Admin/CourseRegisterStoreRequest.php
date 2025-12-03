<?php

namespace App\Http\Requests\Admin;

use App\Constants\PermissionTitle;
use App\Models\Branch;
use App\Models\Course;
use App\Models\PhoneInternal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CourseRegisterStoreRequest extends FormRequest
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
        $course = Course::find($this->course_id);
        $phoneInternal = PhoneInternal::find($this->phone_internal_id);
        $branch = Branch::find($phoneInternal?->phone?->branch_id);

        $validation = [
            'course_id' => ['required', 'exists:courses,id'],
            'clue_id' => ['required', 'exists:clues,id'],
            'phone_internal_id' => ['required', 'exists:phone_internals,id'],
            'description' => ['nullable', 'string'],
            'redirect_back' => ['nullable', 'boolean'],
            'amount' => ['nullable', 'numeric'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ];
        if (!Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_WITHOUT_PAYMENT) and !in_array($this->payment_method_id, [14, 15])) {
            $validation = $validation + [
                'register_paid_amount' => ['required', 'numeric', 'min:' . ($branch?->minimum_pay ?? 1), 'lte:' . $course?->price],
                'payment_description' => ['nullable', 'string'],
            ];
            if (!in_array($this->payment_method_id, [16])) {
                $validation = $validation + [
                    'pay_date' => ['required'],
                    'paid_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2000',
                ];
            }
        }
        return $validation;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'register_paid_amount' => floatval(str_replace(',', '', $this->register_paid_amount)),
            'amount' => floatval(str_replace(',', '', $this->amount)),
        ]);
    }
}
