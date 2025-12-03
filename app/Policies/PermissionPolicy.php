<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_PERMISSION)
            ? Response::allow()
            : Response::deny('شما اجازه دسترسی به این صفحه را ندارید.');
    }

}
