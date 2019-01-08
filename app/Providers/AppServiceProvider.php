<?php

namespace App\Providers;

use App\Services\Routes\RouteChecker;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouteChecker();
    }

    /**
     * Register the route checker facade.
     */
    protected function registerRouteChecker() : void
    {
        $this->app->singleton(RouteChecker::class, function ($app) {
            return new RouteChecker($app['router'], $app['url']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            RouteChecker::class
        ];
    }
}
