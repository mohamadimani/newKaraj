<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\OnlinePayment;
use Illuminate\Http\Request;

class OnlinePaymentController extends Controller
{
    public function index()
    {
        $payments = OnlinePayment::active()->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(30);
        return view('users.online-payments.index', compact('payments'));
    }
}
