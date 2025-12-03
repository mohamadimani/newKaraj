<?php

namespace App\Livewire\Admin\Secretaries;

use Spatie\Permission\Models\Role;
use App\Models\Secretary;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use LivewireAlert;

    public $secretary;
    protected $listeners = ['delete'];
    public $selectedRole;
    public $search = '';

    public function render()
    {
        $roles = Role::all();
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user());
        $permissions = Permission::orderBy('id', 'desc')->get();
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $secretaries->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('CONCAT(first_name," ",last_name)'), 'LIKE', "%$search%")
                    ->orWhere('mobile', 'like', '%' . $search . '%');
            });
        }

        $secretaries = $secretaries->with('user')->orderBy('is_active','desc')->orderBy('id','desc')->paginate(30);

        return view('livewire.admin.secretaries.index', compact('secretaries', 'roles', 'permissions'));
    }

    public function deleteSecretary(Secretary $secretary)
    {
        $this->confirm(__('public.messages.confirm_delete'), [
            'onConfirmed' => 'delete',
        ]);
        $this->secretary = $secretary;
    }

    public function delete()
    {
        $this->secretary->deleted_by = Auth::id();
        $this->secretary->save();
        $this->secretary->delete();
        $this->alert('success', __('public.messages.successfully_done'));
    }

    public function assignPermissions(Secretary $secretary ,$permissionId)
    {
        if ($permissions = Permission::find($permissionId)) {
            if ($secretary->user->givePermissionTo($permissions)) {
                session()->flash('success', 'دسترسی با موفقیت اضافه شد');
                $this->alert('success', 'دسترسی با موفقیت اضافه شد');
            } else {
                session()->flash('error', 'مشکلی در اضافه کردن دسترسی به وجود آمده است');
                $this->alert('error', 'مشکلی در اضافه کردن دسترسی به وجود آمده است');
            }
        } else {
            session()->flash('error', 'یک دسترسی انتخاب کنید');
            $this->alert('error', 'یک دسترسی انتخاب کنید');
        }
    }

    public function removePermission(Secretary $secretary, Permission $permission)
    {
        if ($secretary->user->revokePermissionTo($permission)) {
            session()->flash('success', 'دسترسی با موفقیت حذف شد');
            $this->alert('success', 'دسترسی با موفقیت حذف شد');
        } else {
            session()->flash('error', 'مشکلی در حذف دسترسی به وجود آمده است');
            $this->alert('error', 'مشکلی در حذف دسترسی به وجود آمده است');
        }
    }
}
