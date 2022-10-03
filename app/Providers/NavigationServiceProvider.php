<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Menus\AdminMenu;
use App\Services\Menus\FrontMenu;
use App\Services\Menus\Macros\MenuMacroRegistrar;
use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;

final class NavigationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        MenuMacroRegistrar::register();

        $this->registerAdminMenu();
        $this->registerFrontHeaderMenus();
        $this->registerFrontFooterMenus();
        $this->registerOpenSourceMenu();
    }

    private function registerAdminMenu(): void
    {
        (new AdminMenu(
            'layouts.admin.partials.menu-item-with-icon',
            'layouts.admin.partials.submenu-label',
            'layouts.admin.partials.submenu-item',
        ))->register();
    }

    private function registerFrontHeaderMenus(): void
    {
        (new FrontMenu)->register();
    }

    private function registerFrontFooterMenus(): void
    {
        Menu::macro('footerMain', function () {
            return Menu::new()
                ->route('open-source.packages', __('front.menus.main.open_source'))
                ->route('docs', __('front.menus.main.docs'))
                ->route('contact', __('front.menus.main.contact'))
                ->routeIf(auth()->check() && auth()->user()->can('viewAdminPanel'), 'admin.dashboard', __('front.menus.footer.dashboard'))
                ->setActiveFromRequest()
                ->addClass('mt-4 space-y-3')
                ->addItemClass('text-base text-slate-300 hover:text-white')
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });

        Menu::macro('footerLegal', function () {
            return Menu::new()
                ->route('legal.privacy', __('front.menus.footer.legal_privacy'))
                ->route('legal.terms', __('front.menus.footer.legal_terms'))
                ->route('legal.disclaimer', __('front.menus.footer.legal_disclaimer'))
                ->setActiveFromRequest()
                ->addClass('mt-4 space-y-3')
                ->addItemClass('text-base text-slate-300 hover:text-white')
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });

        Menu::macro('footerOther', function () {
            return Menu::new()
                ->route('uses', __('front.menus.footer.other_uses'))
                ->route('sitemap', __('front.menus.footer.other_sitemap'))
                ->setActiveFromRequest()
                ->addClass('mt-4 space-y-3')
                ->addItemClass('text-base text-slate-300 hover:text-white')
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });
    }

    private function registerOpenSourceMenu(): void
    {
        Menu::macro('openSource', function () {
            return Menu::new()
                ->route('open-source.packages', __('front.menus.open_source.packages'))
                ->route('open-source.projects', __('front.menus.open_source.projects'))
                ->route('open-source.support', __('front.menus.open_source.support'))
                ->setActiveFromRequest('/open-source')
                ->addItemClass('px-3 py-2 font-medium text-sm rounded-md [&:not(.active)]:text-slate-500 hover:bg-slate-400/75 [&:not(.active)]:hover:text-slate-600')
                ->setActiveClass('bg-slate-400/75 text-slate-600 active')
                ->setActiveClassOnLink();
        });
    }
}
