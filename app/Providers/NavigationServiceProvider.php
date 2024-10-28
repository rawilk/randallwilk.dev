<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Menus\FrontMenu;
use App\Services\Menus\Macros\MenuMacroRegistrar;
use Filament\Pages\Dashboard;
use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;

class NavigationServiceProvider extends ServiceProvider
{
    protected const string FOOTER_MENU_CONTAINER_CLASSES = 'footer-list mt-2 lg:mt-4 space-y-1 mx-0 w-3/4';

    protected const string FOOTER_ITEM_CLASSES = 'footer-link text-sm hover:text-gray-950 hover:underline';

    public function register(): void
    {
        MenuMacroRegistrar::register();

        $this->registerFrontHeaderMenus();
        $this->registerFrontFooterMenus();
    }

    protected function registerFrontHeaderMenus(): void
    {
        (new FrontMenu)->register();
    }

    protected function registerFrontFooterMenus(): void
    {
        Menu::macro('footerMain', function () {
            return Menu::new()
                ->route('open-source.packages', __('front.menus.main.open_source'))
                ->route('docs', __('front.menus.main.docs'))
                ->route('contact', __('front.menus.main.contact'))
                ->urlIf(
                    auth()->check() && auth()->user()->isAdmin(),
                    Dashboard::getUrl(panel: 'admin'),
                    __('front.menus.footer.dashboard'),
                )
                ->setActiveFromRequest()
                ->addClass(NavigationServiceProvider::FOOTER_MENU_CONTAINER_CLASSES)
                ->addItemClass(NavigationServiceProvider::FOOTER_ITEM_CLASSES)
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });

        Menu::macro('footerLegal', function () {
            return Menu::new()
                ->route('legal.privacy', __('front.menus.footer.legal_privacy'))
                ->route('legal.terms', __('front.menus.footer.legal_terms'))
                ->route('legal.disclaimer', __('front.menus.footer.legal_disclaimer'))
                ->setActiveFromRequest()
                ->addClass(NavigationServiceProvider::FOOTER_MENU_CONTAINER_CLASSES)
                ->addItemClass(NavigationServiceProvider::FOOTER_ITEM_CLASSES)
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });

        Menu::macro('footerOther', function () {
            return Menu::new()
                ->route('uses', __('front.menus.footer.other_uses'))
                ->route('sitemap', __('front.menus.footer.other_sitemap'))
                ->setActiveFromRequest()
                ->addClass(NavigationServiceProvider::FOOTER_MENU_CONTAINER_CLASSES)
                ->addItemClass(NavigationServiceProvider::FOOTER_ITEM_CLASSES)
                ->setActiveClassOnLink()
                ->setActiveClass('font-semibold');
        });
    }
}
