<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Support\Facades\Gate;

class PhoneController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Phone::class);
        return view('admin.phones.index');
    }
}
