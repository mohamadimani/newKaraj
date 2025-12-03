<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Contracts\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Permission::class);
        return view('admin.permissions.index');
    }
}
