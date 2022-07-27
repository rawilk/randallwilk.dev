<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Menus\AdminMenu;
use Illuminate\Support\ServiceProvider;

final class NavigationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerAdminMenu();
    }

    private function registerAdminMenu(): void
    {
        (new AdminMenu(
            'layouts.admin.partials.menu-item-with-icon',
            'layouts.admin.partials.submenu-label',
            'layouts.admin.partials.submenu-item',
        ))->register();
    }
}
