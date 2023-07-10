<?php

declare(strict_types=1);

namespace App\Services\Menus;

use Spatie\Menu\Laravel\Facades\Menu;

final readonly class AdminMenu
{
    public function __construct(private readonly string $iconView, private readonly string $submenuView, private readonly string $submenuItemView)
    {
    }

    public function register(): void
    {
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
}
