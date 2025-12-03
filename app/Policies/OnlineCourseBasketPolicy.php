<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\User;
use Illuminate\Auth\Access\Response;
class OnlineCourseBasketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_BASKET)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function show(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::SHOW_ONLINE_COURSE_BASKET)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_ONLINE_COURSE_BASKET)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::DESTROY_ONLINE_COURSE_BASKET)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
