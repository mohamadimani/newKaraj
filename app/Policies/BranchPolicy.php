<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BranchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_BRANCH)
        ? Response::allow()
        : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_BRANCH)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_BRANCH)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user, Branch $branch)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_BRANCH)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function update(User $user, Branch $branch)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_BRANCH)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

}
