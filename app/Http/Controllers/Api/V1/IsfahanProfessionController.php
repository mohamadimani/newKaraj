<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\IsfahanCourseCollection;
use App\Http\Resources\IsfahanProfessionCollection;
use App\Models\Branch;
use App\Models\Profession;

class IsfahanProfessionController extends Controller
{
    public function index()
    {
        $isfahanBranch = Branch::where('name', 'LIKE', '%اصفهان%')->first();
        $professions = $isfahanBranch->professions()->active()->orderBy('id', 'desc')->paginate(10000);

        return apiResponse()
            ->message(__('لیست حرفه ها'))
            ->data(new IsfahanProfessionCollection($professions))
            ->send();
    }

    public function courses(Profession $profession)
    {
        $courses = $profession->courses()->active()->where('start_date', '>=', now()->format('Y-m-d'))->orderBy('id', 'desc')->paginate(10000);
        return apiResponse()
            ->message(__('دوره های حرفه'))
            ->data(new IsfahanCourseCollection($courses))
            ->send();
    }
}
