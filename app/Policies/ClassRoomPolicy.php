<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassRoomPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_CLASS_ROOM)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_CLASS_ROOM)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_CLASS_ROOM)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_CLASS_ROOM)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_CLASS_ROOM)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::DELETE_CLASS_ROOM)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
