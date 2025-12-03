<?php

namespace App\Policies;

use App\Constants\PermissionTitle;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(PermissionTitle::INDEX_STUDENT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function edit(User $user, Student $student)
    {
        return $user->hasPermissionTo(PermissionTitle::EDIT_STUDENT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function update(User $user, Student $student)
    {
        return $user->hasPermissionTo(PermissionTitle::UPDATE_STUDENT)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
    public function uploadStudentDocuments(User $user, Student $student)
    {
        return $user->hasPermissionTo(PermissionTitle::UPLOAD_STUDENT_DOCUMENTS)
            ? Response::allow()
            : Response::deny('شما دسترسی برای مشاهده این صفحه را ندارید');
    }
}
