<?php

namespace App\Repositories\User;

use App\Constants\PermissionTitle;
use App\Models\Secretary;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class SecretaryRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Secretary::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_SECRETARY;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('phoneInternals', function(Builder $q) use ($user) {
            $q->whereHas('phone', function(Builder $phonesQuery) use ($user) {
                $phonesQuery->whereIn('branch_id', $user->secretary->branchIds());
            });
        });
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('phoneInternals', function(Builder $q) use ($user) {
            $q->whereHas('phone', function(Builder $phonesQuery) use ($user) {
                $phonesQuery->where('branch_id', $user->clerk->branch_id);
            });
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
