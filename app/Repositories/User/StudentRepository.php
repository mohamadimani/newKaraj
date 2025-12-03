<?php

namespace App\Repositories\User;

use App\Constants\PermissionTitle;
use App\Models\Student;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class StudentRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Student::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_STUDENT;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('user', function (Builder $query) use ($user) {
            $query->whereHas('clue', function (Builder $q) use ($user) {
                $q->whereIn('branch_id', $user->secretary->branchIds());
            });
        });
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('user', function (Builder $query) use ($user) {
            $query->whereHas('clue', function (Builder $q) use ($user) {
                $q->where('branch_id', $user->clerk->branch_id);
            });
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
