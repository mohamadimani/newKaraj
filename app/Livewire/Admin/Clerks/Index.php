<?php

namespace App\Livewire\Admin\Clerks;

use App\Models\Clerk;
use App\Models\Role;
use App\Models\User;
use App\Repositories\User\ClerkRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $permission_id;
    protected $paginationTheme = 'bootstrap';

    use WithPagination, WithFileUploads, LivewireAlert;
    public function render()
    {
        $clerkRepository = resolve(ClerkRepository::class);
        $clerks = $clerkRepository->getListQuery(Auth::user());
        $permissions = Permission::orderBy('id', 'desc')->get();
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $clerks->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%');
            })
                ->orWhereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }

        $clerks = $clerks->with('user', 'branch')->orderBy('id', 'desc')->paginate(30);

        return view('livewire.admin.clerks.index', compact('clerks', 'permissions'));
    }

    public function assignPermissions(Clerk $clerk, $permissionId)
    {
        if ($permissions = Permission::find($permissionId) and $clerk->user->givePermissionTo($permissions)) {
            session()->flash('success', 'دسترسی با موفقیت اضافه شد');
            $this->alert('success', 'دسترسی با موفقیت اضافه شد');
        } else {
            session()->flash('error', 'مشکلی در اضافه کردن دسترسی به وجود آمده است');
            $this->alert('error', 'مشکلی در اضافه کردن دسترسی به وجود آمده است');
        }
    }

    public function removePermission(Clerk $clerk, Permission $permission)
    {
        if ($clerk->user->revokePermissionTo($permission)) {
            session()->flash('success', 'دسترسی با موفقیت حذف شد');
            $this->alert('success', 'دسترسی با موفقیت حذف شد');
        } else {
            session()->flash('error', 'مشکلی در حذف دسترسی به وجود آمده است');
            $this->alert('error', 'مشکلی در حذف دسترسی به وجود آمده است');
        }
    }
}
