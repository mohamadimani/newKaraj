<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PhonePolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_PHONE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
