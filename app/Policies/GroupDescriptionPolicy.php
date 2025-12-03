<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\GroupDescription;
use App\Models\User;
use Illuminate\Auth\Access\Response;
class GroupDescriptionPolicy
{

    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_GROUP_DESCRIPTION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_GROUP_DESCRIPTION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_GROUP_DESCRIPTION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function edit(User $user, GroupDescription $groupDescription)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_GROUP_DESCRIPTION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function update(User $user, GroupDescription $groupDescription)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_GROUP_DESCRIPTION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
