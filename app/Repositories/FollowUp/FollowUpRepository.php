<?php

namespace App\Repositories\FollowUp;

use App\Constants\PermissionTitle;
use App\Models\FollowUp;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class FollowUpRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return FollowUp::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_FOLLOW_UP;
    }

    protected function queryHasSecretaryOwenedData(Builder $query, ?User $user): Builder
    {
        return $this->queryHasEndUser($query, $user);
    }

    protected function queryHasClerkOwenedData(Builder $query, ?User $user): Builder
    {
        return $this->queryHasEndUser($query, $user);
    }

    protected function queryHasEndUser(Builder $query, ?User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }
}
