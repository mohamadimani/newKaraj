<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GroupDescriptionStoreRequest;
use App\Http\Requests\Admin\GroupDescriptionUpdateRequest;
use App\Models\GroupDescription;
use App\Models\Profession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class GroupDescriptionController extends Controller
{
    public function index()
    {
        Gate::authorize('index', GroupDescription::class);
        return view('admin.group-descriptions.index');
    }

    public function create()
    {
        Gate::authorize('create', GroupDescription::class);
        
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        return view('admin.group-descriptions.create', compact('professions'));
    }

    public function store(GroupDescriptionStoreRequest $request)
    {
        Gate::authorize('store', GroupDescription::class);
        try {
            DB::beginTransaction();
            $groupDescription = GroupDescription::create([
                'description' => $request->validated()['description'],
        ]);

        foreach ($request->validated()['profession_ids'] as $index => $professionId) {
            $groupDescription->professions()->attach($professionId, [
                'sort' => $index + 1,
            ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('group_descriptions.messages.error_occurred'));
        }

        return redirect()->route('group-descriptions.index')->with('success', __('group_descriptions.messages.successfully_created'));
    }

    public function edit(GroupDescription $groupDescription)
    {
        Gate::authorize('edit', $groupDescription);
        
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        return view('admin.group-descriptions.edit', compact('groupDescription', 'professions'));
    }

    public function update(GroupDescription $groupDescription, GroupDescriptionUpdateRequest $request)
    {
        Gate::authorize('update', $groupDescription);
        $groupDescription->update([
            'description' => $request->validated()['description'],
        ]);

        $oldProfessions = $groupDescription->professions->pluck('id')->toArray();
        $groupDescription->professions()->detach();

        foreach ($request->validated()['profession_ids'] as $index => $professionId) {
            $sort = $index + 1;
            if (in_array($professionId, $oldProfessions)) {
                $sort = $request->validated()['sort'][$index];
            }

            $groupDescription->professions()->attach($professionId, [
                'sort' => $sort,
            ]);
        }

        return redirect()->route('group-descriptions.index')->with('success', __('group_descriptions.messages.successfully_updated'));
    }
}
