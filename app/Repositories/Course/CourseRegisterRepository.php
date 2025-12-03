<?php

namespace App\Repositories\Course;

use App\Constants\PermissionTitle;
use App\Models\CourseRegister;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class CourseRegisterRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return CourseRegister::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_COURSE_REGISTER;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereIn('internal_branch_id', $user->secretary->branchIds());
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->where('internal_branch_id', $user->clerk->branch_id);
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
