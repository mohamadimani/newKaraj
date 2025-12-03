<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClerkPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_CLERK)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_CLERK)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_CLERK)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_CLERK)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_CLERK)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::DELETE_CLERK)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
