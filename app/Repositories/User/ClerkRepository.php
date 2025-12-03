<?php

namespace App\Repositories\User;

use App\Constants\PermissionTitle;
use App\Models\Clerk;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class ClerkRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Clerk::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_CLERK;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $query->where('id', -1);
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
