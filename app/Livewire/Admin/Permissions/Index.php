<?php

namespace App\Livewire\Admin\Permissions;

use App\Constants\PermissionTitle;
use App\Constants\PermissionTitleFa;
use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use ReflectionClass;

class Index extends Component
{
    public string $search = '';
    public $permission_name = '';
    public $permission_name_edit = '';
    public $permission_id_edit = null;

    use LivewireAlert, WithPagination;

    public function render()
    {
        $search = '%' . $this->search . '%';
        $permissions = Permission::where('name', 'like', $search)->orderBy('id', 'desc')->get();
        return view('livewire.admin.permissions.index', compact('permissions'));
    }

    public function store()
    {
        $this->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $this->permission_name,
        ]);

        $this->permission_name = '';
        $this->alert('success', 'دسترسی با موفقیت ایجاد شد');
    }

    public function edit(Permission $permission)
    {
        $this->permission_id_edit = $permission->id;
        $this->permission_name_edit = $permission->name;
    }

    public function update(Permission $permission)
    {
        $this->validate([
            'permission_name_edit' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->name = $this->permission_name_edit;
        if ($permission->save()) {
            $this->permission_id_edit = null;
            $this->permission_name_edit = '';
            $this->alert('success', 'دسترسی با موفقیت ویرایش شد');
        } else {
            $this->alert('error', 'مشکلی در ویرایش دسترسی به وجود آمده است');
        }
    }

    public function syncPermissions()
    {
        $permissions = new ReflectionClass(PermissionTitle::class);
        $permissions = $permissions->getConstants();
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                ]);
            }
        }
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->syncPermissions($permissions);
        Artisan::call('optimize:clear');
        $this->alert('success', 'یکپارچه سازی دسترسی ها با موفقیت انجام شد');
    }
}
