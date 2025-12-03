<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfessionStoreRequest;
use App\Http\Requests\Admin\ProfessionUpdateRequest;
use App\Models\Branch;
use App\Models\Profession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfessionController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Profession::class);
        return view('admin.professions.index');
    }

    public function create()
    {
        Gate::authorize('create', Profession::class);
        $branches = Branch::all();

        return view('admin.professions.create', compact('branches'));
    }

    public function store(ProfessionStoreRequest $request)
    {
        Gate::authorize('store', Profession::class);
        $profession = Profession::create([
            'title' => $request->title,
            'public_price' => $request->public_price,
            'public_duration_hours' => $request->public_duration_hours,
            'public_capacity' => $request->public_capacity,
            'private_price' => $request->private_price,
            'private_duration_hours' => $request->private_duration_hours,
            'private_capacity' => $request->private_capacity,
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);
        $profession->branches()->sync($request->branch_ids);

        return redirect()->route('professions.index')->with('success', __('professions.messages.successfully_created'));
    }

    public function edit($id)
    {
        Gate::authorize('edit', Profession::class);
        $profession = Profession::find($id);
        $branches = Branch::all();

        return view('admin.professions.edit', compact('profession', 'branches'));
    }

    public function update(ProfessionUpdateRequest $request, $id)
    {
        Gate::authorize('update', Profession::class);
        $profession = Profession::find($id);
        $profession->update($request->validated());
        $profession->branches()->sync($request->branch_ids);

        return redirect()->route('professions.index')->with('success', __('professions.messages.successfully_updated'));
    }
}
