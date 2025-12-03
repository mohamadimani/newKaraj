<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\OnlineCourse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OnlineCoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_ONLINE_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_ONLINE_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_ONLINE_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_ONLINE_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::DESTROY_ONLINE_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
