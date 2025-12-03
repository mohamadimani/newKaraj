<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCollection;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::query()->active()->where('start_date', '>=', now()->format('Y-m-d'))->with('branch')->orderBy('id', 'desc')->paginate(10000);
        return apiResponse()
            ->message(__('لیست دوره های حضوری'))
            ->data(new CourseCollection($course))
            ->send();
    }
}
