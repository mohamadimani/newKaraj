<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\CourseRegister;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CourseRegisterPolicy
{


    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_COURSE_REGISTER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_COURSE_REGISTER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_COURSE_REGISTER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function cancel(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CANCEL_COURSE_REGISTER)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
