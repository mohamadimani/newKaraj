<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TechnicalController extends Controller
{

    public function index()
    {
        Gate::authorize('index', Technical::class);
        return view('admin.technicals.index');
    }

    public function introduced()
    {
        Gate::authorize('index', Technical::class);
        return view('admin.technicals.introduced');
    }
}
