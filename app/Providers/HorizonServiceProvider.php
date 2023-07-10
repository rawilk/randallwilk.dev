<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function gate(): void
    {
        Gate::define('viewHorizon', fn ($user) => $user->email === Config::get('services.horizon.email'));
    }
}
