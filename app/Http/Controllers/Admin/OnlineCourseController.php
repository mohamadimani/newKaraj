<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Http\Requests\StoreOnlineCourseRequest;
use App\Http\Requests\UpdateOnlineCourseRequest;
use App\Models\Teacher;
use Illuminate\Support\Facades\Gate;

class OnlineCourseController extends Controller
{
    public $teacher_id;
    public $percent;
    public function index()
    {
        Gate::authorize('index', OnlineCourse::class);
        return view('admin.online-courses.index');
    }

    public function create()
    {
        Gate::authorize('create', OnlineCourse::class);
        $techers = Teacher::active()->get();
        return view('admin.online-courses.create', compact('techers'));
    }

    public function store(StoreOnlineCourseRequest $request)
    {
        Gate::authorize('store', OnlineCourse::class);
        if (OnlineCourse::where('spot_key', $request->spot_key)->first()) {
            return redirect()->back()->with('error', 'کلید اسپات پلیر تکراری است!');
        }
        OnlineCourse::create([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'spot_key' => $request->spot_key,
            'duration_hour' => $request->duration_hour,
            'discount_amount' => (empty($request->discount_amount) ? 0 : $request->discount_amount),
            'discount_start_at_jalali' => $request->discount_start_at_jalali ?? null,
            'discount_expire_at_jalali' => $request->discount_expire_at_jalali ?? null,
            'discount_start_at' => $request->discount_start_at_jalali ? jalaliToTimestamp($request->discount_start_at_jalali) : null,
            'discount_expire_at' => $request->discount_expire_at_jalali ? jalaliToTimestamp($request->discount_expire_at_jalali) : null,
            'created_by' => auth()->user()->id,
            'teacher_id' =>  $request->teacher_id,
            'percent' =>  $request->percent,
        ]);
        return redirect()->route('online-courses.index')->with('success', 'دوره آنلاین با موفقیت اضافه شد!');
    }

    public function edit(OnlineCourse $onlineCourse)
    {
        Gate::authorize('edit', OnlineCourse::class);
        $techers = Teacher::active()->get();
        return view('admin.online-courses.edit', compact('onlineCourse', 'techers'));
    }
    public function update(UpdateOnlineCourseRequest $request, OnlineCourse $onlineCourse)
    {
        Gate::authorize('update', OnlineCourse::class);
        $onlineCourse->update([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'spot_key' => $request->spot_key,
            'duration_hour' => $request->duration_hour,
            'discount_amount' => (empty($request->discount_amount) ? 0 : $request->discount_amount),
            'discount_start_at_jalali' => $request->discount_start_at_jalali ?? null,
            'discount_expire_at_jalali' => $request->discount_expire_at_jalali ?? null,
            'discount_start_at' => $request->discount_start_at_jalali ? jalaliToTimestamp($request->discount_start_at_jalali) : null,
            'discount_expire_at' => $request->discount_expire_at_jalali ? jalaliToTimestamp($request->discount_expire_at_jalali) : null,
            'category_id' => $onlineCourse->category_id,
            'updated_by' => auth()->user()->id,
            'teacher_id' =>  $request->teacher_id,
            'percent' =>  $request->percent,
        ]);
        return redirect()->route('online-courses.index')->with('success', 'دوره آنلاین با موفقیت ویرایش شد!');
    }

    public function destroy(OnlineCourse $onlineCourse)
    {
        Gate::authorize('destroy', OnlineCourse::class);
        $onlineCourse->deleted_by = auth()->user()->id;
        $onlineCourse->save();
        if ($onlineCourse->delete()) {
            return redirect()->route('online-courses.index')->with('success', 'دوره آنلاین با موفقیت حذف شد!');
        }
        return redirect()->route('online-courses.index')->with('error', 'مشکلی در حذف دوره آنلاین رخ داده است!');
    }

    public function smsMarketing()
    {
        return view('admin.online-courses.sms-marketing');
    }
}
