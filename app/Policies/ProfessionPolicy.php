<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfessionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_PROFESSION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_PROFESSION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_PROFESSION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_PROFESSION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_PROFESSION)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
