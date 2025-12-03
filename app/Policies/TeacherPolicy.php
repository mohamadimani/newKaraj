<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeacherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_TEACHER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_TEACHER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_TEACHER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_TEACHER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function update(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_TEACHER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
