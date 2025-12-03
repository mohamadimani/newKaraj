<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOnlineCourseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'spot_key' => 'required|string|max:255|unique:online_courses,spot_key,' . $this->onlineCourse->id . ',id,deleted_at,NULL,is_active,1',
            'amount' => 'required|numeric',
            'duration_hour' => 'required|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_start_at_jalali' => 'nullable|string',
            'discount_expire_at_jalali' => 'nullable|string',
            'description' => 'nullable|string',
            'category_id' => 'nullable|numeric',
            'teacher_id' => 'required|numeric',
            'percent' => 'required|numeric|min:0|max:100',
        ];
    }
}
