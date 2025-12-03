<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Models\Branch;
use App\Models\Province;
use Illuminate\Support\Facades\Gate;

class BranchController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Branch::class);
        return view('admin.branches.index');
    }

    public function create()
    {
        Gate::authorize('create', Branch::class);
        $provinces = Province::all();
        return view('admin.branches.create', compact('provinces'));
    }

    public function store(BranchStoreRequest $request)
    {
        Gate::authorize('store', Branch::class);
        $branch = Branch::create(array_merge($request->all(), [
            'created_by' => auth()->id(),
        ]));

        if ($branch) {
            session()->flash('success', 'ثبت شد');
            return redirect()->route('admin.branches.index');
        } else {
            return redirect()->back()->with('error', 'ثبت نشد');
        }
    }


    public function edit(Branch $branch)
    {
        Gate::authorize('edit', $branch);
        $provinces = Province::all();
        return view('admin.branches.edit', compact('branch', 'provinces'));
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        Gate::authorize('update', $branch);
        if ($this->updateBranch($request, $branch)) {
            session()->flash('success', ' ویرایش شد ' . $request->name);
            return redirect()->route('admin.branches.index');
        } else {
            return redirect()->back()->with('error', 'ویرایش نشد');
        }
    }

    private function updateBranch(BranchUpdateRequest $request, Branch $branch)
    {
        return $branch->update([
            'name' => $request->name,
            'address' => $request->address,
            'site' => $request->site,
            'bank_card_number' => $request->bank_card_number,
            'bank_card_name' => $request->bank_card_name,
            'bank_card_owner' => $request->bank_card_owner,
            'minimum_pay' => $request->minimum_pay,
            'online_pay_link' => $request->online_pay_link,
            'manager' => $request->manager,
            'created_by' => auth()->id(),
            'province_id' => $request->province_id,
        ]);
    }
}
