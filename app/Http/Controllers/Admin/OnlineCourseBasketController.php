<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\OnlineCourseBasket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OnlineCourseBasketController extends Controller
{
    public function index()
    {
        Gate::authorize('index', OnlineCourseBasket::class);

        return view('admin.online-course-baskets.index');
    }

    public function show(User $user)
    {
        Gate::authorize('show', OnlineCourseBasket::class);

        $onlineCourseBaskets = OnlineCourseBasket::with(['onlineCourse', 'user'])
            ->where('user_id', $user->id)
            ->get();

        $onlineCourses = OnlineCourse::orderBy('id', 'desc')->get();
        return view('admin.online-course-baskets.show', compact('onlineCourseBaskets', 'onlineCourses', 'user'));
    }

    public function store(Request $request, User $user)
    {
        Gate::authorize('store', OnlineCourseBasket::class);

        $request->validate([
            'online_course_id' => 'required|exists:online_courses,id',
        ]);

        if ($onlineCourseBasket = OnlineCourseBasket::where('user_id', $user->id)->where('online_course_id', $request->online_course_id)->first()) {
            session()->flash('error', 'این دوره در سبد خرید موجود است');
            return redirect()->route('online-course-baskets.show', $user->id);
        }

        $onlineCourseBasket = OnlineCourseBasket::create([
            'user_id' => $user->id,
            'online_course_id' => $request->online_course_id,
        ]);

        if ($onlineCourseBasket) {
            session()->flash('success', 'دوره با موفقیت به سبد خرید اضافه شد');
        } else {
            session()->flash('error', 'خطایی رخ داده است');
        }

        return redirect()->route('online-course-baskets.show', $user->id);
    }

    public function destroy(OnlineCourseBasket $onlineCourseBasket)
    {
        Gate::authorize('destroy', OnlineCourseBasket::class);

        $onlineCourseBasket->delete();
        session()->flash('success', 'دوره از سبد خرید حذف شد');
        return redirect()->route('online-course-baskets.show', $onlineCourseBasket->user->id);
    }
}
