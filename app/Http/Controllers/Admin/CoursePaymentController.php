<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePayment;
use Illuminate\Http\Request;

class CoursePaymentController extends Controller
{
    
    /**
     * Display a listing of the course payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = CoursePayment::with(['user', 'course'])->latest()->paginate(30);
        
        return view('admin.course-payments.index', compact('payments'));
    }
}
