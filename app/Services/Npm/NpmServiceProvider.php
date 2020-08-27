<?php

namespace App\Services\Npm;

use Illuminate\Support\ServiceProvider;

class NpmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NpmApi::class, fn () => new NpmApi);
    }
}
