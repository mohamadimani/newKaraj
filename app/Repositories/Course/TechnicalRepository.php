<?php

namespace App\Repositories\Course;

use App\Constants\PermissionTitle;
use App\Models\Technical;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class TechnicalRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Technical::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_TECHNICAL;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        // return $query->whereHas('courseRegister', function(Builder $q) use ($user) {
        //     $q->where('secretary_id', $user->secretary->id);
        // });

        return $query->whereIn('branch_id', $user->secretary->branchIds());
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('courseRegister', function(Builder $q) use ($user) {
            $q->where('internal_branch_id', $user->clerk->branch_id);
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
