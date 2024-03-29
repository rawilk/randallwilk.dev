<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $home = $this->isFrontendPage(Str::lower(Request::getRequestUri()))
            ? 'home'
            : 'admin.dashboard';

        Breadcrumbs::before(
            fn (Generator $trail) => $trail->push(__('Home'), route($home))
        );
    }

    private function isFrontendPage(string $requestUri): bool
    {
        return ! Str::startsWith($requestUri, '/admin');
    }
}
