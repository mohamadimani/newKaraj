<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\ProfessionCollection;
use App\Http\Resources\ProfessionResource;
use App\Models\Profession;

class ProfessionController extends Controller
{
    public function index()
    {
        $professions = Profession::query()->active()->orderBy('id', 'desc')->paginate(10000);
        return apiResponse()
            ->message(__('لیست حرفه ها'))
            ->data(new ProfessionCollection($professions))
            ->send();
    }

    public function show(Profession $profession)
    {
        return apiResponse()
            ->message(__('حرفه'))
            ->data(new ProfessionResource($profession))
            ->send();
    }

    public function courses(Profession $profession)
    {
        $courses = $profession->courses()->active()->where('start_date', '>=', now()->format('Y-m-d'))->orderBy('id', 'desc')->paginate(10000);
        return apiResponse()
            ->message(__('دوره های حرفه'))
            ->data(new CourseCollection($courses))
            ->send();
    }
}
