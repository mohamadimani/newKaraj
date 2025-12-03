<?php

namespace App\Repositories\Goods;

use App\Constants\PermissionTitle;
use App\Models\Goods;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class GoodsRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Goods::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_GOODS;
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
