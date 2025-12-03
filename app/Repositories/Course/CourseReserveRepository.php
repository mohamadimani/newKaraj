<?php

namespace App\Repositories\Course;

use App\Constants\PermissionTitle;
use App\Models\CourseReserve;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class CourseReserveRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return CourseReserve::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_COURSE_RESERVE;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('clue', function (Builder $query) use ($user) {
            $query->whereIn('branch_id', $user->secretary->branchIds());
        });
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('clue', function (Builder $query) use ($user) {
            $query->where('branch_id', $user->clerk->branch_id);
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
