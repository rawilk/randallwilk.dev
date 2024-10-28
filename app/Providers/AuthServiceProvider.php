<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Support\Auth\CustomUserProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerAdminGate();

        Password::defaults(function (): Password {
            return Password::min(8)
                ->when($this->app->isProduction(), function (Password $rule): void {
                    $rule->uncompromised();
                });
        });
    }

    public function register(): void
    {
        parent::register();

        $this->app->bind(
            StatefulGuard::class,
            fn () => Auth::guard('web'),
        );

        Auth::provider('customEloquent', function ($app, array $config) {
            return new CustomUserProvider($app->make('hash'), $config['model']);
        });
    }

    protected function registerAdminGate(): void
    {
        Gate::after(function (User $user): Response {
            if ($user->isAdmin()) {
                return Response::allow();
            }

            return Response::deny();
        });
    }
}
