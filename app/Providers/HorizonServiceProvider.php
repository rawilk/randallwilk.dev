<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function gate(): void
    {
        Gate::define('viewHorizon', fn (User $user) => $user->email === config('services.horizon.email'));
    }
}
