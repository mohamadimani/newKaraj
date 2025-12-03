<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\OnlineCourse;
use App\Models\OnlineCourseBasket;
use App\Models\OrderItem;

class OnlineCourseController extends Controller
{
    public function index()
    {
        return view('users.online-courses.index');
    }

    public function show(OnlineCourse $onlineCourse)
    {
        if ($onlineCourse->category_id) {
            $sameOnlineCourses = OnlineCourse::where('category_id', $onlineCourse->category_id)->whereNot('id', $onlineCourse->id)->get();
        } else {
            $sameOnlineCourses = [];
        }
        $discount = $this->getActiveDiscount();
        return view('users.online-courses.show', compact('onlineCourse', 'sameOnlineCourses', 'discount'));
    }

    public function addToCart(OnlineCourse $onlineCourse)
    {
        if ($onlineCourseOrder = OrderItem::where('user_id', auth()->user()->id)->where('online_course_id', $onlineCourse->id)->where('license_key', '!=', null)->count()) {
            session()->flash('error', 'شما قبلا این دوره را خریداری کرده اید');
            return redirect()->route('user.online-courses.show', $onlineCourse->id);
        }
        if ($onlineCourseOrder = OrderItem::where('user_id', auth()->user()->id)->where('online_course_id', $onlineCourse->id)->where('license_key', null)->count()) {
            session()->flash('error', 'این دوره در   سفارش های پرداخت نشده شما موجود است');
            return redirect()->route('user.online-courses.show', $onlineCourse->id);
        }
        if ($onlineCourseBasket = OnlineCourseBasket::where('user_id', auth()->user()->id)->where('online_course_id', $onlineCourse->id)->count()) {
            session()->flash('error', 'این دوره در سبد خرید موجود است');
            return redirect()->route('user.online-courses.show', $onlineCourse->id);
        }
        $onlineCourseBasket = OnlineCourseBasket::create([
            'user_id' => auth()->user()->id,
            'online_course_id' => $onlineCourse->id,
        ]);
        if ($onlineCourseBasket) {
            session()->flash('success', 'دوره به سبد خرید اضافه شد');
            return redirect()->route('user.online-courses.show', $onlineCourse->id);
        } else {
            session()->flash('error', 'مشکلی پیش آمده است');
            return redirect()->back();
        }
    }

    private function getActiveDiscount()
    {
        $discount =  Discount::where('is_online', true)->where('is_active', true)->where('discount_type', 'public')->where('available_from', '<=', now())->where('available_until', '>=', now())->whereNotNull('banner')->first();
        if ($discount and $discount->used_count >= $discount->usage_limit) {
            return null;
        }
        return $discount;
    }
}
