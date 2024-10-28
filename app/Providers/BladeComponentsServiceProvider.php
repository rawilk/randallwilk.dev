<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class BladeComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $blade->component('layouts.front.base', 'page');
            $blade->component('layouts.docs.base', 'doc-page');
        });
    }
}
