<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\CourseReserve;
use App\Models\User;
use Illuminate\Auth\Access\Response;
class CourseReservePolicy
{

    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_COURSE_RESERVE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::CREATE_COURSE_RESERVE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function store(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::STORE_COURSE_RESERVE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function edit(User $user, CourseReserve $courseReserve)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_COURSE_RESERVE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function convertToCourseView(User $user, CourseReserve $courseReserve)
    {
        return $user->hasPermissionTo(PermissionTitle::CONVERT_TO_COURSE_VIEW)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function convertToCourse(User $user, CourseReserve $courseReserve)
    {
        return $user->hasPermissionTo(PermissionTitle::CONVERT_TO_COURSE)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
