<?php

declare(strict_types=1);

namespace App\Services\Menus;

use App\Services\Menus\Macros\ExpandableMenu;
use Spatie\Menu\Laravel\Facades\Menu;
use Spatie\Menu\Laravel\View;

final class AdminMenu
{
    private const MACROS = [
        ExpandableMenu::class,
    ];

    public function __construct(private string $iconView, private string $submenuView) {}

    public function register(): void
    {
        $this->registerMacros();

        $this->registerMainMenu();
    }

    private function registerMainMenu(): void
    {
        $iconView = $this->iconView;
        $submenuView = $this->submenuView;

        Menu::macro('adminMain', function () use ($iconView, $submenuView) {
            $menu = (new AdminMenuItems(Menu::new(), $iconView, $submenuView))->register();

            return $menu
                ->withoutWrapperTag()
                ->withoutParentTag()
                ->setActiveFromRequest()
                ->setActiveClassOnLink();
        });
    }

    private function registerMacros(): void
    {
        /** @var \App\Services\Menus\Macros\MenuMacro $macro */
        foreach (self::MACROS as $macro) {
            (new $macro)->register();
        }
    }
}
