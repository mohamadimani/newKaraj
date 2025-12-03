<?php

namespace App\Repositories\Branch;

use App\Constants\PermissionTitle;
use App\Models\Branch;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class BranchRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Branch::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_BRANCH;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereIn('id', $user->secretary->branchIds());
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->where('id', $user->clerk->branch_id);
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
