<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassRoomStoreRequest;
use App\Http\Requests\Admin\ClassRoomUpdateRequest;
use App\Models\ClassRoom;
use App\Models\Branch;
use App\Models\Profession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('index', ClassRoom::class);

        return view('admin.class-rooms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', ClassRoom::class);
        $branches = Branch::active()->get();

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        return view('admin.class-rooms.create', compact('branches', 'professions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassRoomStoreRequest $request)
    {
        Gate::authorize('store', ClassRoom::class);

        $classRoom = ClassRoom::create([
            ...$request->validated(),
            'created_by' => Auth::id()
        ]);

        $classRoom->professions()->attach($request->validated('profession_id'));

        return redirect()->route('class-rooms.index')->with('success', __('class_rooms.successfully_created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        Gate::authorize('edit', $classRoom);
        $branches = Branch::active()->get();
       
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();
        
        return view('admin.class-rooms.edit', compact('classRoom', 'branches', 'professions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassRoomUpdateRequest $request, ClassRoom $classRoom)
    {
        Gate::authorize('update', $classRoom);

        $classRoom->update($request->validated());
        $classRoom->professions()->sync($request->validated('profession_id'));

        return redirect()->route('class-rooms.index')->with('success', __('class_rooms.successfully_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        Gate::authorize('destroy', $classRoom);
        $classRoom->delete();
        return redirect()->route('class-rooms.index')->with('success', __('class_rooms.successfully_deleted'));
    }
}
