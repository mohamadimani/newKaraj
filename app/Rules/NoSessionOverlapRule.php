<?php

namespace App\Rules;

use App\Models\Course;
use App\Models\CourseSession;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSessionOverlapRule implements ValidationRule
{
    public function __construct(
        protected $startDate,
        protected $endDate,
        protected $weekDays,
        protected $startTime,
        protected $endTime,
        protected $courseId = null,
        protected $classRoomId,
        protected $branchId,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $courseSessionDates = Course::calculateCourseSessionDates(
            $this->startDate,
            $this->endDate,
            $this->weekDays,
            $this->startTime,
            $this->endTime
        );

        $courseSessionDates = array_column($courseSessionDates, 'session_date');

        $overlappingSession = CourseSession::query()->whereIn('session_date', $courseSessionDates)
            ->where('session_start_time', '<', $this->endTime)
            ->where('session_end_time', '>', $this->startTime)
            ->when($this->courseId, function ($query) {
                $query->where('course_id', '!=', $this->courseId);
            })
            ->whereHas('course', function ($query) {
                $query->where('class_room_id', $this->classRoomId);
                $query->where('branch_id', $this->branchId);
            })
            ->first();
        if ($overlappingSession) {
            $fail(__('validation.has_session_overlap', ['course' => $overlappingSession->course->title]));
        }
    }
}
