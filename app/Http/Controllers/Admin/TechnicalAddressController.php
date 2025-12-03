<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechnicalAddressStoreRequest;
use App\Models\Branch;
use App\Models\Province;
use App\Models\TechnicalAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TechnicalAddressController extends Controller
{
    public function index()
    {
        Gate::authorize('index', TechnicalAddress::class);
        $branches = Branch::active()->get();
        $provinces = Province::all();
        return view('admin.technical-address.index', compact('branches', 'provinces'));
    }
}
