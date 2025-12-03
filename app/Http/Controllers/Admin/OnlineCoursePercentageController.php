<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OnlineCoursePercentageController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Teacher::class);

        return view('admin.online-course-percentages.index');
    }
}

