<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourseGroup;
use App\Http\Requests\StoreOnlineCourseGroupRequest;
use App\Models\OnlineCourse;
use Illuminate\Support\Facades\Gate;

class OnlineCourseGroupController extends Controller
{
    public function index()
    {
        Gate::authorize('index', OnlineCourseGroup::class);

        $onlineCourseGroups = OnlineCourseGroup::all();

        return view('admin.online-course-groups.index', compact('onlineCourseGroups'));
    }

    public function create()
    {
        Gate::authorize('create', OnlineCourseGroup::class);

        $onlineCourses = OnlineCourse::where('category_id', null)->orderBy('id', 'DESC')->get();

        return view('admin.online-course-groups.create', compact('onlineCourses'));
    }

    public function store(StoreOnlineCourseGroupRequest $request)
    {
        Gate::authorize('store', OnlineCourseGroup::class);

        $onlineCourseGroup = OnlineCourseGroup::create([
            'name' => $request->name,
            'created_by' => auth()->user()->id,
        ]);

        foreach ($request->online_courses as $onlineCourse) {
            $onlineCourse = OnlineCourse::find($onlineCourse);
            $onlineCourse->category_id = $onlineCourseGroup->id;
            $onlineCourse->save();
        }
        return redirect()->route('online-course-groups.index')->with('success', 'گروه جدید با موفقیت ایجاد شد');
    }


    public function destroy(OnlineCourseGroup $onlineCourseGroup)
    {
        Gate::authorize('destroy', OnlineCourseGroup::class);

        $onlineCourseGroup->onlineCourses()->update(['category_id' => null]);
        $onlineCourseGroup->delete();
        return redirect()->route('online-course-groups.index')->with('success', 'گروه با موفقیت حذف شد');
    }
}
