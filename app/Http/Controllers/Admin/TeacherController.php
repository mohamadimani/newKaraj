<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherStoreRequest;
use App\Http\Requests\Admin\TeacherUpdateRequest;
use App\Models\Branch;
use App\Models\Profession;
use App\Models\Province;
use App\Models\Teacher;
use App\Models\User;
use App\Repositories\Profession\ProfessionRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Teacher::class);

        return view('admin.teachers.index');
    }

    public function create()
    {
        Gate::authorize('create', Teacher::class);

        $provinces = Province::all();
        $branches = Branch::active()->get();

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        return view('admin.teachers.create', compact('provinces', 'branches', 'professions'));
    }

    public function store(TeacherStoreRequest $request)
    {
        Gate::authorize('store', Teacher::class);
        DB::beginTransaction();

        try {
            $user = $this->createUser($request);
            $teacher = $this->createTeacher($request, $user);

            $teacher->branches()->sync($request->branch_ids);
            $teacher->professions()->sync($request->profession_ids);
            $teacher->user->update(['province_id' => $request->province_id]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', __('teachers.successfully_created'));
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Teacher $teacher)
    {
        Gate::authorize('edit', $teacher);

        $teacher = $teacher->load('user');
        $provinces = Province::all();
        $branches = Branch::active()->get();

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        return view('admin.teachers.edit', compact('teacher', 'provinces', 'branches', 'professions'));
    }

    public function update(TeacherUpdateRequest $request, Teacher $teacher)
    {
        Gate::authorize('update', $teacher);

        $this->updateTeacher($request, $teacher);
        $this->updateUser($request, $teacher);

        $teacher->branches()->sync($request->branch_ids);
        $teacher->professions()->sync($request->profession_ids);

        return redirect()->route('teachers.index')->with('success', __('teachers.successfully_updated'));
    }

    private function createUser(TeacherStoreRequest $request): User
    {
        if ($user = User::where('mobile', formatMobile($request->mobile))->first()) {
            return $user;
        }
        return User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => formatMobile($request->mobile),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            'is_admin' => true,
            'created_by' => Auth::id(),
        ]);
    }

    private function createTeacher(TeacherStoreRequest $request, User $user): Teacher
    {
        $role = Role::where('name', 'teacher')->first();
        $user->assignRole($role);
        $user->givePermissionTo($role->permissions);
        return Teacher::create([
            'user_id' => $user->id,
            'start_date' => $request->start_date ? toGeorgianDate($request->start_date) : null,
            'created_by' => Auth::id(),
            'province_id' => $request->province_id,
        ]);
    }

    private function updateTeacher(TeacherUpdateRequest $request, Teacher $teacher): bool
    {
        return $teacher->update([
            'start_date' => $request->start_date ? toGeorgianDate($request->start_date) : null,
        ]);
    }

    private function updateUser(TeacherUpdateRequest $request, Teacher $teacher): bool
    {
        return $teacher->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => formatMobile($request->mobile),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            'province_id' => $request->province_id,
        ]);
    }
}
