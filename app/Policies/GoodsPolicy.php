<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GoodsPolicy
{

    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_GOODS)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_GOODS)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_GOODS)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function reportsStore(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::REPORTS_STORE_GOODS)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
