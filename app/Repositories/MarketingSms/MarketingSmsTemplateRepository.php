<?php

namespace App\Repositories\MarketingSms;

use App\Constants\PermissionTitle;
use App\Models\MarketingSms\MarketingSmsTemplate;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class MarketingSmsTemplateRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return MarketingSmsTemplate::class;
    }

    public function permission(): string
    {
        return PermissionTitle::ADMIN_MARKETING_SMS_TEMPLATE;
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
