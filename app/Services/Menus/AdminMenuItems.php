<?php

declare(strict_types=1);

namespace App\Services\Menus;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\View;

final class AdminMenuItems
{
    public function __construct(private Menu $menu, private string $iconView, private string $submenuView)
    {
    }

    public function register(): Menu
    {
        $this
            ->addDashboard()
            ->addRepositories()
            ->addUsers();

        return $this->menu;
    }

    private function addDashboard(): self
    {
        $this->menu->add(
            View::create($this->iconView, [
                'label' => __('Dashboard'),
                'url' => route('admin.dashboard'),
                'icon' => 'heroicon-o-home',
            ])
        );

        return $this;
    }

    private function addRepositories(): self
    {
        $this->menu->add(
            View::create($this->iconView, [
                'label' => __('repositories.page_title'),
                'url' => route('admin.repositories'),
                'icon' => 'heroicon-s-code',
            ])
        );

        return $this;
    }

    private function addUsers(): self
    {
        $this->menu->add(
            View::create($this->iconView, [
                'label' => __('users.page_title'),
                'url' => route('admin.users'),
                'icon' => 'css-user-list',
            ])
        );

        return $this;
    }
}
