<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseStoreRequest;
use App\Http\Requests\Admin\CourseUpdateRequest;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\PaymentMethod;
use App\Models\Profession;
use App\Models\Teacher;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Course::class);

        return view('admin.courses.index');
    }

    public function create()
    {
        Gate::authorize('create', Course::class);

        $weekDays = Course::weekDays();
        return view('admin.courses.create', compact('weekDays'));
    }

    public function store(CourseStoreRequest $request)
    {
        Gate::authorize('store', Course::class);

        Course::createObject($request->validated());
        return redirect()->route('courses.index')->with('success', __('courses.successfully_created'));
    }

    public function edit(Course $course)
    {
        Gate::authorize('edit', Course::class);
        $branches = Branch::active()->get();
        $classRooms = ClassRoom::active()->with('branch')->get();
        $teachers = Teacher::active()->with('user')->get();

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        $weekDays = Course::weekDays();

        return view('admin.courses.edit', compact('course', 'branches', 'classRooms', 'teachers', 'professions', 'weekDays'));
    }

    public function update(Course $course, CourseUpdateRequest $request)
    {
        Gate::authorize('update', Course::class);

        Course::updateObject($course, $request->validated());
        return redirect()->route('courses.index')->with('success', __('courses.successfully_updated'));
    }

    public function courseStudents(Course $course)
    {
        return view('admin.courses.course-students', compact('course'));
    }
}
