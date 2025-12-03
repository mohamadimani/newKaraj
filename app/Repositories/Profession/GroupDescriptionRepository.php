<?php

namespace App\Repositories\Profession;

use App\Constants\PermissionTitle;
use App\Models\GroupDescription;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class GroupDescriptionRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return GroupDescription::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_GROUP_DESCRIPTION;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('professions', function ($q) use ($user) {
            $q->whereHas('branches', function(Builder $branchQuery) use ($user) {
                $branchQuery->whereIn('branches.id', $user->secretary->branchIds());
            });
        });
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('professions', function ($q) use ($user) {
            $q->whereHas('branches', function(Builder $branchQuery) use ($user) {
                $branchQuery->where('branches.id', $user->clerk->branch_id);
            });
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
