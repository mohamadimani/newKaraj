<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Contracts\Role;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Role::class);
        return view('admin.roles.index');
    }
}
