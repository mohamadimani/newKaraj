<?php

namespace App\Observers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseObserver
{
    public function creating(Course $course)
    {
        $course->created_by = Auth::id();
    }

    public function created(Course $course)
    {
        $course->generateSessions();
        $course->createPriceLog();
    }

    public function updated(Course $course)
    {
        if ($course->wasChanged(['start_date', 'end_date', 'start_time', 'end_time', 'week_days'])) {
            $course->updateSessions();
        }

        
        if ($course->wasChanged(['price'])) {
            $course->createPriceLog();
        }
    }
}
