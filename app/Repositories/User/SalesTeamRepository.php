<?php

namespace App\Repositories\User;

use App\Constants\PermissionTitle;
use App\Models\Clue;
use App\Models\SalesTeam;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class SalesTeamRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return SalesTeam::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_SALES_TEAM;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereIn('branch_id', $user->secretary->branchIds());
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->where('branch_id', $user->clerk->branch_id);
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
