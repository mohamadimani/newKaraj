<?php

namespace App\Providers;

use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);

        Paginator::useBootstrap();

        // db listener and add log for query that take mor than 1 secound time
        DB::listen(function ($query) {
            if ($query->time > 1000) { // یک ثانیه
                Log::warning('🚨🚨🚨🚨🚨🚨 Slow Query Detected: ' . $query->sql, [
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            }
        });
    }
}
