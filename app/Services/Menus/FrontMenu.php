<?php

declare(strict_types=1);

namespace App\Services\Menus;

use Illuminate\Support\Arr;
use Spatie\Menu\Laravel\Facades\Menu;
use Spatie\Menu\Laravel\Menu as LaravelMenu;
use Spatie\Menu\Laravel\View;
use Spatie\Menu\Link;

final class FrontMenu
{
    private const FLYOUT_BUTTON_VIEW = 'layouts.front.partials.navigation.flyout-button';

    private const FLYOUT_ITEM_VIEW = 'layouts.front.partials.navigation.flyout-item';

    private const FLYOUT_ITEM_FOOTER_VIEW = 'layouts.front.partials.navigation.flyout-footer-item';

    public function register(): void
    {
        $this->registerMainMenu();
    }

    private function registerMainMenu(): void
    {
        $flyoutButtonView = self::FLYOUT_BUTTON_VIEW;
        $flyoutItemView = self::FLYOUT_ITEM_VIEW;
        $flyoutFooterView = self::FLYOUT_ITEM_FOOTER_VIEW;

        Menu::macro('main', function (array $attributes = []) use ($flyoutButtonView, $flyoutItemView, $flyoutFooterView) {
            $isMobile = Arr::get($attributes, 'mobile', false);

            return Menu::new()
                ->submenuIf(
                    ! $isMobile,
                    View::create($flyoutButtonView, [
                        'label' => __('front.menus.main.open_source'),
                        'active' => request()->routeIs('open-source.*'),
                    ]),
                    function (LaravelMenu $menu) use ($flyoutItemView, $flyoutFooterView) {
                        return $menu->flyout(function (LaravelMenu $menu) use ($flyoutItemView) {
                            $menu->view($flyoutItemView, [
                                'label' => __('front.menus.open_source.packages'),
                                'url' => route('open-source.packages'),
                                'description' => __('front.menus.open_source.packages_description'),
                                'active' => request()->routeIs('open-source.packages'),
                            ])
                                ->view($flyoutItemView, [
                                    'label' => __('front.menus.open_source.projects'),
                                    'url' => route('open-source.projects'),
                                    'description' => __('front.menus.open_source.projects_description'),
                                ]);
                        }, function (LaravelMenu $menu) use ($flyoutFooterView) {
                            $menu->view($flyoutFooterView, [
                                'url' => route('open-source.support'),
                                'label' => __('front.menus.open_source.support'),
                                'description' => __('front.menus.open_source.support_description'),
                            ]);
                        });
                    }
                )
                ->routeIf($isMobile, 'open-source.packages', __('front.menus.open_source.packages'))
                ->routeIf($isMobile, 'open-source.projects', __('front.menus.open_source.projects'))
                ->routeIf($isMobile, 'open-source.support', __('front.menus.open_source.support'))
                ->route('docs', __('front.menus.main.docs'))
                ->route('contact', __('front.menus.main.contact'))
                ->setActiveFromRequest();
        });

        Menu::macro('mobileService', function () {
            return Menu::new()
                ->route('profile.show', __('users.profile.page_title'))
                ->routeIfCan('viewAdminPanel', 'admin.dashboard', __('front.menus.footer.dashboard'))
                ->routeIfCan('viewHorizon', 'horizon.index', __('View Horizon'))
                ->add(
                    Link::to('#', __('Sign Out'))->setAttribute('x-on:click.prevent', 'document.getElementById(\'logout-form\').submit()')
                )
                ->withoutWrapperTag()
                ->withoutParentTag()
                ->setActiveFromRequest();
        });
    }
}
