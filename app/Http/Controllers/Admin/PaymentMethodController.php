<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Gate;
class PaymentMethodController extends Controller
{
    public function index()
    {
        Gate::authorize('index', PaymentMethod::class);
        return view('admin.payment-methods.index');
    }
}
