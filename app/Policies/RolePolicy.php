<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\User;
use Illuminate\Auth\Access\Response;
class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_ROLE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
