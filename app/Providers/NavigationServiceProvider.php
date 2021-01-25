<?php

namespace App\Providers;

use App\Services\Menus\AdminMenu;
use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\View;

class NavigationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Menu::macro('main', function (array $properties = []) {
            return Menu::new()
                ->route('open-source.packages', __('front.menus.main.open_source'))
                ->route('contact', __('front.menus.main.contact'))
                ->route('docs', __('front.menus.main.docs'))
                ->setActiveFromRequest()
                ->addClass($properties['class'] ?? '');
        });

        Menu::macro('footerMain', function () {
            return Menu::new()
                ->route('open-source.packages', __('front.menus.main.open_source'))
                ->route('contact', __('front.menus.main.contact'))
                ->route('docs', __('front.menus.main.docs'))
                ->routeIf(auth()->check() && auth()->user()->is_admin, 'admin.dashboard', __('front.menus.footer.dashboard'))
                ->setActiveFromRequest()
                ->addClass('mt-4 space-y-4')
                ->addItemClass('text-base text-blue-gray-300 hover:text-white')
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });

        Menu::macro('footerLegal', function () {
            return Menu::new()
                ->route('legal.privacy', __('front.menus.footer.legal_privacy'))
                ->route('legal.disclaimer', __('front.menus.footer.legal_disclaimer'))
                ->setActiveFromRequest()
                ->addClass('mt-4 space-y-4')
                ->addItemClass('text-base text-blue-gray-300 hover:text-white')
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });

        Menu::macro('service', function () {
            return Menu::new()
                ->addIf(! auth()->check(), View::create('layouts.partials.navigation.login', ['url' => route('login')]))
                ->addIf(auth()->check(), View::create('layouts.partials.navigation.user'))
                ->addIf(auth()->check(), View::create('layouts.partials.navigation.logout-form'))
                ->withoutWrapperTag()
                ->withoutParentTag()
                ->setActiveClassOnLink()
                ->setActiveFromRequest();
        });

        (new AdminMenu(
            iconView: 'layouts.admin.partials.menu-item-with-icon',
            submenuView: 'layouts.admin.partials.submenu-label',
        ))->register();
    }
}
