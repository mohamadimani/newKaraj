<?php

namespace App\Repositories\Profession;

use App\Constants\PermissionTitle;
use App\Models\Profession;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class ProfessionRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Profession::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_PROFESSION;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('branches', function ($query) use ($user) {
            $query->whereIn('branches.id', $user->secretary->branchIds());
        });
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->whereHas('branches', function ($query) use ($user) {
            $query->where('branches.id', $user->clerk->branch_id);
        });
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
