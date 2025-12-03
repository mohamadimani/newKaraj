<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\User;
use Illuminate\Auth\Access\Response;
class FamiliarityWayPolicy
{
    /**
     * Create a new policy instance.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_FAMILIARITY_WAY)
        ? Response::allow()
        : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
