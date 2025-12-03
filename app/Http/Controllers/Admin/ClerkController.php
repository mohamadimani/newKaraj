<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClerkStoreRequest;
use App\Http\Requests\Admin\ClerkUpdateRequest;
use App\Models\Branch;
use App\Models\Clerk;
use App\Models\Province;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class ClerkController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Clerk::class);

        return view('admin.clerks.index');
    }

    public function create()
    {
        Gate::authorize('create', Clerk::class);
        $provinces = Province::all();
        $branches = Branch::all();

        return view('admin.clerks.create', compact('provinces', 'branches'));
    }

    public function store(ClerkStoreRequest $request)
    {
        Gate::authorize('store', Clerk::class);
        DB::beginTransaction();

        try {
            $user = $this->createUser($request);
            $clerk = $this->createClerk($request, $user);

            DB::commit();

            return redirect()->route('clerks.index')
                ->with('success', __('clerks.messages.successfully_created'));
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Clerk $clerk)
    {
        //
    }

    public function edit(Clerk $clerk)
    {
        Gate::authorize('edit', $clerk);
        $provinces = Province::all();
        $branches = Branch::all();

        return view('admin.clerks.edit', compact('clerk', 'provinces', 'branches'));
    }

    public function update(ClerkUpdateRequest $request, Clerk $clerk)
    {
        Gate::authorize('update', $clerk);
        DB::beginTransaction();

        try {
            $this->updateUser($request, $clerk);
            $this->updateClerk($request, $clerk);

            DB::commit();

            return redirect()->route('clerks.index')
                ->with('success', __('clerks.messages.successfully_updated'));
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function createUser(ClerkStoreRequest $request): User
    {
        if ($user = User::where('mobile', formatMobile($request->mobile))->first()) {
            return $user;
        }

        return User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => formatMobile($request->mobile),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            'is_active' => true,
            'is_admin' => true,
            'created_by' => Auth::id(),
            'province_id' => $request->province_id ?? null,
        ]);
    }

    private function createClerk(ClerkStoreRequest $request, User $user): Clerk
    {
        $hasClerk = $this->checkUserHasClerkAccount($user, $request);
        if ($hasClerk) {
            throw  new Exception('برای این شماره در این شعبه قبلا کارمند ایجاد شده است');
        }
        $role = Role::where('name', 'clerk')->first();
        $user->assignRole($role);
        $user->givePermissionTo($role->permissions);
        return Clerk::create([
            'user_id' => $user->id,
            'start_date' => $request->start_date ? toGeorgianDate($request->start_date) : null,
            'created_by' => Auth::id(),
            'is_active' => $request->has('is_active'),
            'branch_id' => $request->branch_id ?? null,
        ]);
    }

    private function checkUserHasClerkAccount(User $user, ClerkStoreRequest $request)
    {
        if (Clerk::where('user_id', $user->id)->where('branch_id', $request->branch_id)->first()) {
            return true;
        }
        return false;
    }

    private function updateUser(ClerkUpdateRequest $request, Clerk $clerk): bool
    {
        return $clerk->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => formatMobile($request->mobile),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            'province_id' => $request->province_id,
        ]);
    }

    private function updateClerk(ClerkUpdateRequest $request, Clerk $clerk): bool
    {
        return $clerk->update([
            'start_date' => $request->start_date ? toGeorgianDate($request->start_date) : null,
            'is_active' => $request->is_active,
            'branch_id' => $request->branch_id,
        ]);
    }
}
