<?php

namespace App\Livewire\Admin\Roles;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    public string $role_name = '';
    public string $role_name_edit = '';
    public $role_id_edit = null;
    public $permissions_id = null;
    use LivewireAlert, WithPagination;

    public function render()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        $permissions = Permission::orderBy('id', 'desc')->get();
        return view('livewire.admin.roles.index', compact('roles', 'permissions'));
    }

    public function store()
    {
        $this->validate([
            'role_name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'name' => $this->role_name,
        ]);
        $this->role_name = '';
        $this->alert('success', 'نقش با موفقیت ایجاد شد');
    }

    public function edit(Role $role)
    {
        $this->role_id_edit = $role->id;
        $this->role_name_edit = $role->name;
    }

    public function update(Role $role)
    {
        $this->validate([
            'role_name_edit' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->name = $this->role_name_edit;
        if ($role->save()) {
            $this->role_id_edit = null;
            $this->role_name_edit = '';
            $this->alert('success', 'نقش با موفقیت ویرایش شد');
        } else {
            $this->alert('error', 'مشکلی در ویرایش نقش به وجود آمده است');
        }
    }

    public function assignPermissions(Role $role, $permissionId)
    {
        if ($permissions = Permission::find($permissionId) and $role->givePermissionTo($permissions)) {
            session()->flash('success', 'دسترسی با موفقیت اضافه شد');
            $this->alert('success', 'دسترسی با موفقیت اضافه شد');
        } else {
            session()->flash('error', 'مشکلی در اضافه کردن دسترسی به وجود آمده است');
            $this->alert('error', 'مشکلی در اضافه کردن دسترسی به وجود آمده است');
        }
    }

    public function removePermission(Role $role, Permission $permission)
    {
        if ($role->revokePermissionTo($permission)) {
            session()->flash('success', 'دسترسی با موفقیت حذف شد');
            $this->alert('success', 'دسترسی با موفقیت حذف شد');
        } else {
            session()->flash('error', 'مشکلی در حذف دسترسی به وجود آمده است');
            $this->alert('error', 'مشکلی در حذف دسترسی به وجود آمده است');
        }
    }
}
