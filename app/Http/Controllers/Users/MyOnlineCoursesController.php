<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class MyOnlineCoursesController extends Controller
{
    public function index()
    {
        $orderItem = OrderItem::active()->paid()->where('user_id', auth()->user()->id)->with('onlineCourse')->get();
        return view('users.my-courses.index', compact('orderItem'));
    }
}
