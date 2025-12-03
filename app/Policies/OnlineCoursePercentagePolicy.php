<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OnlineCoursePercentagePolicy
{
    /**
     * Create a new policy instance.
     */
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_PERCENTAGE)
        ? Response::allow()
        : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
