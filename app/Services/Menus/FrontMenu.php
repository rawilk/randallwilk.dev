<?php

declare(strict_types=1);

namespace App\Services\Menus;

use Illuminate\Support\Arr;
use Spatie\Menu\Laravel\Menu as LaravelMenu;
use Spatie\Menu\Laravel\View;

class FrontMenu
{
    protected const string FLYOUT_BUTTON_VIEW = 'layouts.front.partials.navigation.flyout-button';

    protected const string FLYOUT_ITEM_VIEW = 'layouts.front.partials.navigation.flyout-item';

    protected const string FLYOUT_ITEM_FOOTER_VIEW = 'layouts.front.partials.navigation.flyout-footer-item';

    public function register(): void
    {
        $this->registerMainMenu();
    }

    protected function registerMainMenu(): void
    {
        $flyoutButtonView = self::FLYOUT_BUTTON_VIEW;
        $flyoutItemView = self::FLYOUT_ITEM_VIEW;
        $flyoutFooterView = self::FLYOUT_ITEM_FOOTER_VIEW;

        LaravelMenu::macro('main', function (array $attributes = []) use ($flyoutButtonView, $flyoutItemView, $flyoutFooterView) {
            $isMobile = Arr::get($attributes, 'mobile', false);

            return LaravelMenu::new()
                ->submenuIf(
                    ! $isMobile,
                    View::create($flyoutButtonView, [
                        'label' => __('front.menus.main.open_source'),
                        'active' => request()->routeIs('open-source.*'),
                    ]),
                    function (LaravelMenu $menu) use ($flyoutItemView, $flyoutFooterView) {
                        $menu->flyout(function (LaravelMenu $menu) use ($flyoutItemView) {
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
                ->routeIf($isMobile, 'home', 'Home')
                ->routeIf($isMobile, 'open-source.packages', __('front.menus.open_source.packages'))
                ->routeIf($isMobile, 'open-source.projects', __('front.menus.open_source.projects'))
                ->routeIf($isMobile, 'open-source.support', __('front.menus.open_source.support'))
                ->setItemAttribute('wire:navigate')
                ->route('docs', __('front.menus.main.docs'))
                ->route('contact', __('front.menus.main.contact'))
                ->setActiveFromRequest();
        });
    }
}
