<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;

class NavigationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Menu::macro('main', function (array $properties = []) {
            return Menu::new()
                ->route('open-source.packages', 'Open Source')
                ->route('contact', 'Contact')
                ->setActiveFromRequest()
                ->addClass($properties['class'] ?? '');
        });

        Menu::macro('service', function () {
            return Menu::new()
                ->url('https://docs.randallwilk.dev', 'Docs')
                ->withoutWrapperTag()
                ->withoutParentTag()
                ->setActiveClassOnLink()
                ->setActiveFromRequest();
        });

        Menu::macro('opensource', function () {
            return Menu::new()
                ->route('open-source.packages', 'Packages')
                ->route('open-source.projects', 'Projects')
                ->setActiveFromRequest('/open-source')
                ->setActiveClass('submenu-active');
        });
    }
}
