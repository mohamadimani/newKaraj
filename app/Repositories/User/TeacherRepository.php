<?php

namespace App\Repositories\User;

use App\Constants\PermissionTitle;
use App\Models\Teacher;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class TeacherRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Teacher::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_TEACHER;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('branches', function(Builder $q) use ($user) {
            $q->whereIn('branches.id', $user->secretary->branchIds());
        });
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('branches', function(Builder $q) use ($user) {
            $q->where('branches.id', $user->clerk->branch_id);
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
