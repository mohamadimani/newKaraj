<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class SecretaryPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_SECRETARY)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_SECRETARY)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_SECRETARY)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_SECRETARY)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_SECRETARY)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::DELETE_SECRETARY)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
