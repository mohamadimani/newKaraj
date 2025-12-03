<?php

namespace App\Policies;

use App\Models\User;
use App\Constants\PermissionTitle;
use Illuminate\Auth\Access\Response;
class CluePolicy
{
    /**
     * Create a new policy instance.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_CLUE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_CLUE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_CLUE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_CLUE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_CLUE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
