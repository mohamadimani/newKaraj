<?php

namespace App\Policies;

use App\Models\User;
use App\Constants\PermissionTitle;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_PAYMENT)
            ? Response::allow()
            : Response::deny('شما اجازه دسترسی به این صفحه را ندارید.');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_PAYMENT)
            ? Response::allow()
            : Response::deny('شما اجازه دسترسی به این صفحه را ندارید.');
    }
}
