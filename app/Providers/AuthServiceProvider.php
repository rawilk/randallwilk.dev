<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

final class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('viewAdminPanel', function ($user) {
            // For now, only my super admin account can do this.
            return $user?->isSuperAdmin() ?? false;
        });
    }
}
