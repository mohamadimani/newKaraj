<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

abstract class AbstractRepository extends BaseRepository
{
    /**
     * @return string
     */
    abstract public function permission(): string;

    /**
     * @param Builder            $query Attribute Type Query.
     * @param User|null $user  User.
     *
     * @return Builder
     */
    abstract protected function queryHasSecretaryOwenedData(Builder $query, User|null $user): Builder;

    /**
     * @param Builder            $query Attribute Type Query.
     * @param User|null $user  User.
     *
     * @return Builder
     */
    abstract protected function queryHasClerkOwenedData(Builder $query, User|null $user): Builder;

    /**
     * @param Builder            $query Query.
     * @param User|null $user  User.
     *
     * @return Builder
     */
    abstract protected function queryHasEndUser(Builder $query, ?User $user): Builder;

    /**
     * @param Builder $query Builder.
     *
     * @return Builder
     */
    protected function queryHasAdmin(Builder $query): Builder
    {
        return $query;
    }

    /**
     * @param User|null $user User.
     *
     * @return Builder
     */
    public function getListQuery(User|null $user = null): Builder
    {
        $query = $this->model()::query();
        if (is_null($user) && !$user = Auth::user()) {
            return $query->where('id', -1);
        }

        if ($this->hasPermissionAdmin($user)) {
            return $this->queryHasAdmin($query);
        } elseif ($this->hasPersmissionSecretary($user)) {
            return $this->queryHasSecretaryOwenedData($query, $user);
        } elseif ($this->hasPersmissionClerk($user)) {
            return $this->queryHasClerkOwenedData($query, $user);
        }

        return $this->queryHasEndUser($query, $user);
    }

    /**
     * @param User|null $user User.
     *
     * @return mixed
     */
    protected function hasPermissionAdmin(User|null $user): bool
    {
        return empty($user) ? false : $user->hasPermissionTo($this->permission());
    }

    /**
     * @param User|null $user User.
     *
     * @return bool
     */
    private function hasPersmissionSecretary(User|null $user): bool
    {
        return empty($user) ? false : optional($user)->isSecretary();
    }

    /**
     * @param User|null $user User.
     *
     * @return bool
     */
    private function hasPersmissionClerk(User|null $user): bool
    {
        return empty($user) ? false : optional($user)->isClerk();
    }
}
