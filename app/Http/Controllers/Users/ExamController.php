<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegister;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $courseRegisters = user()->student->courseRegisters()->with('course')->get();
        return view('users.exams.index', compact('courseRegisters'));
    }

    public function show(Exam $exam, CourseRegister $courseRegister)
    {
        return view('users.exams.show', compact('exam', 'courseRegister'));
    }
}
