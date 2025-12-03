<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineCourseCollection;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;

class OnlineCourseController extends Controller
{
    public function index()
    {
        $onlineCourse = OnlineCourse::query()->active()->orderBy('id', 'desc')->paginate(1000);
        return apiResponse()
            ->message(__('لیست دوره های آنلاین'))
            ->data(new OnlineCourseCollection($onlineCourse))
            ->send();
    }
}
