<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

final class BladeComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            foreach (Config::get('app-blade-components.components', []) as $alias => $component) {
                $blade->component($component['class'], $alias);
            }

            $blade->component('layouts.base', 'page');
        });
    }
}
