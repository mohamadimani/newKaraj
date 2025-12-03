<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseRegister;
use Illuminate\Support\Facades\Gate;

class CourseCancelController extends Controller
{
    public function index()
    {
        Gate::authorize('cancel', CourseRegister::class);
        return view('admin.course-cancels.index');
    }
}
