<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SecretaryStoreRequest;
use App\Http\Requests\Admin\SecretaryUpdateRequest;
use App\Models\Phone;
use App\Models\PhoneInternal;
use App\Models\Province;
use App\Models\Secretary;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class SecretaryController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Secretary::class);
        return view('admin.secretaries.index');
    }

    public function create()
    {
        Gate::authorize('create', Secretary::class);
        $phones = Phone::active()->with('branch')->get();
        $provinces = Province::all();

        return view('admin.secretaries.create', compact('phones', 'provinces'));
    }

    public function store(SecretaryStoreRequest $request)
    {
        Gate::authorize('store', Secretary::class);
        DB::beginTransaction();

        try {
            $user = $this->createUser($request);
            $secretary = $this->createSecretary($request, $user);
            $this->attachPhonesToSecretary($request, $secretary);

            DB::commit();

            return redirect()->route('secretaries.index')
                ->with('success', __('secretaries.successfully_created'));
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Secretary $secretary)
    {
        Gate::authorize('edit', Secretary::class);
        $phones = PhoneInternal::active()->with('phone.branch')->get();
        $provinces = Province::all();

        return view('admin.secretaries.edit', compact('secretary', 'phones', 'provinces'));
    }

    public function update(SecretaryUpdateRequest $request, Secretary $secretary)
    {
        Gate::authorize('update', Secretary::class);
        $this->updateSecretary($request, $secretary);
        $this->updateUser($request, $secretary);
        $secretaryPhones = $secretary->phoneInternals->pluck('id')->toArray();
        $addedPhones = array_diff($request->phones, $secretaryPhones);
        $removedPhones = array_diff($secretaryPhones, $request->phones);

        PhoneInternal::whereIn('id', $addedPhones)->update(['secretary_id' => $secretary->id]);
        PhoneInternal::whereIn('id', $removedPhones)->update(['secretary_id' => null]);

        return redirect()->route('secretaries.index')->with('success', __('secretaries.successfully_updated'));
    }

    public function destroy(Secretary $secretary)
    {
        Gate::authorize('delete', Secretary::class);
        try {
            $secretary->delete();

            return redirect()->route('secretaries.index')
                ->with('success', __('secretaries.successfully_deleted'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function createUser(SecretaryStoreRequest $request): User
    {
        if ($user = User::where('mobile', formatMobile($request->mobile))->first()) {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'is_admin' => true,
                'created_by' => Auth::id(),
                'province_id' => $request->province_id,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            ]);
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
            'province_id' => $request->province_id,
        ]);
    }

    private function createSecretary(SecretaryStoreRequest $request, User $user): Secretary
    {
        $secretary = Secretary::where('user_id', $user->id)->where('is_active', true)->first();
        if ($secretary) {
            return $secretary;
        }
        $role = Role::where('name', 'secretary')->first();
        $user->assignRole($role);
        $user->givePermissionTo($role->permissions);
        return Secretary::create([
            'user_id' => $user->id,
            'start_date' => $request->start_date ? toGeorgianDate($request->start_date) : null,
            'created_by' => Auth::id(),
            'is_active' => $request->has('is_active'),
        ]);
    }

    private function updateSecretary(SecretaryUpdateRequest $request, Secretary $secretary): bool
    {
        Gate::authorize('updateSecretary', Secretary::class);
        return $secretary->update([
            'start_date' => $request->start_date ? toGeorgianDate($request->start_date) : null,
            'is_active' => $request->is_active,
        ]);
    }

    private function updateUser(SecretaryUpdateRequest $request, Secretary $secretary): bool
    {
        return $secretary->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => formatMobile($request->mobile),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            'province_id' => $request->province_id,
            'is_admin' => true,
        ]);
    }

    private function attachPhonesToSecretary(SecretaryStoreRequest $request, Secretary $secretary): void
    {
        foreach ($request->phones as $phone) {
            $phoneModel = Phone::find($phone);
            $phoneInternal = PhoneInternal::create([
                'number' => time(),
                'phone_id' => $phoneModel->id,
                'title' => $request->first_name . ' ' . $request->last_name . ' - ' . $phoneModel->branch->name,
                'secretary_id' => $secretary->id,
                'created_by' => Auth::id(),
            ]);
        }
    }
}
