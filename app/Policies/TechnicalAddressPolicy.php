<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\TechnicalAddress;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TechnicalAddressPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): Response
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_TECHNICAL_ADDRESS)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

}
