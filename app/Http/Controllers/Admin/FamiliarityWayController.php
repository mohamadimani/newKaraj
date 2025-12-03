<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\FamiliarityWay;

class FamiliarityWayController extends Controller
{
    public function index()
    {
        Gate::authorize('index', FamiliarityWay::class);
        return view('admin.familiarity-ways.index');
    }
}
