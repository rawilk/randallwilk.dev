<?php

declare(strict_types=1);

namespace App\Services\Menus;

use App\Services\Menus\Macros\ExpandableMenu;
use Spatie\Menu\Laravel\Facades\Menu;

final class AdminMenu
{
    /**
     * @var <int, class-string<\App\Services\Menus\Macros\MenuMacro>>
     */
    private const MACROS = [
        ExpandableMenu::class,
    ];

    public function __construct(private readonly string $iconView, private readonly string $submenuView, private readonly string $submenuItemView)
    {
    }

    public function register(): void
    {
        $this->registerMacros();
        $this->registerMainMenu();
    }

    private function registerMainMenu(): void
    {
        $iconView = $this->iconView;
        $submenuView = $this->submenuView;
        $submenuItemView = $this->submenuItemView;

        Menu::macro('adminMenu', function () use ($iconView, $submenuView, $submenuItemView) {
            $menu = (new AdminMenuItems(Menu::new(), $iconView, $submenuView, $submenuItemView))->register();

            return $menu
                ->withoutWrapperTag()
                ->withoutParentTag()
                ->setActiveFromRequest()
                ->setActiveClassOnLink();
        });
    }

    private function registerMacros(): void
    {
        foreach (self::MACROS as $macro) {
            (new $macro)->register();
        }
    }
}
