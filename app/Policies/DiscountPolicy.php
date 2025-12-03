<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Auth\Access\Response;
class DiscountPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_DISCOUNT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_DISCOUNT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_DISCOUNT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function edit(User $user, Discount $discount)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_DISCOUNT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function update(User $user, Discount $discount)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_DISCOUNT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
