<?php

declare(strict_types=1);

namespace App\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

final class BladeComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('app', [
                'path' => resource_path('svg'),
                'prefix' => 'svg',
            ]);
        });
    }

    public function boot(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $blade->component('layouts.admin.base', 'admin-app');
        });
    }
}
