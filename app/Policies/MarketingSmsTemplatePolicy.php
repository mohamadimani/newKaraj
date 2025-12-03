<?php

namespace App\Policies;

use App\Models\User;
use App\Constants\PermissionTitle;
use Illuminate\Auth\Access\Response;
class MarketingSmsTemplatePolicy
{
    /**
     * Create a new policy instance.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_MARKETING_SMS_TEMPLATE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function settings(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::SETTINGS_MARKETING_SMS_TEMPLATE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_MARKETING_SMS_TEMPLATE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_MARKETING_SMS_TEMPLATE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_MARKETING_SMS_TEMPLATE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_MARKETING_SMS_TEMPLATE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
