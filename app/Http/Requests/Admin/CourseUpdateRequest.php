<?php

namespace App\Http\Requests\Admin;

use App\Enums\Course\CourseTypeEnum;
use App\Rules\NoSessionOverlapRule;
use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i|before:end_time',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'week_days' => 'required|array',
            'week_days.*' => 'required|integer|min:0|max:6',
            'profession_id' => 'required|exists:professions,id',
            'teacher_id' => 'required|exists:teachers,id',
            'branch_id' => 'required|exists:branches,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'course_type' => 'required|in:' . implode(',', CourseTypeEnum::values()),
            'price' => ['required', 'numeric', 'min:' . ($branch?->minimum_pay ?? 1)],
            'duration_hours' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:0',
            'start_date' => new NoSessionOverlapRule(
                $this->start_date,
                $this->end_date,
                $this->week_days,
                $this->start_time,
                $this->end_time,
                $this->course->id,
                $this->class_room_id,
                $this->branch_id
            ),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => floatval(str_replace(',', '', $this->price)),
            'start_date' => toGeorgianDate($this->start_date),
            'end_date' => toGeorgianDate($this->end_date),
        ]);
    }
}
