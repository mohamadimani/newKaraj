<?php

namespace App\Repositories\Branch;

use App\Constants\PermissionTitle;
use App\Models\Phone;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class PhoneRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Phone::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_PHONE;
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
