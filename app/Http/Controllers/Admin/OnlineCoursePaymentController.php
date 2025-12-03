<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePayment;
use Illuminate\Support\Facades\Gate;

class OnlineCoursePaymentController extends Controller
{
    public function index()
    {
        Gate::authorize('index', OnlinePayment::class);

        return view('admin.online-course-payments.index');
    }
}
