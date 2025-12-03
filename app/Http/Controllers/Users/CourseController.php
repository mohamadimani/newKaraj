<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('users.courses.index');
    }

    public function show(Course $course)
    {
        return view('users.courses.show', compact('course'));
    }
}
