<?php

declare(strict_types=1);

namespace App\Services\Menus;

use App\Enums\PermissionEnum;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Support\Facades\Auth;
use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\View;

final class AdminMenuItems
{
    private ?User $user;

    public function __construct(
        private readonly Menu $menu,
        private readonly string $iconView,
        private readonly string $submenuView,
        private readonly string $submenuItemView,
    ) {
        $this->user = Auth::user();
    }

    public function register(): Menu
    {
        $this->addDashboard()
            ->addRepositories()
            ->addUserManagement();

        return $this->menu;
    }

    private function addDashboard(): self
    {
        $this->menu->add(
            View::create($this->iconView, [
                'label' => __('dashboard.title'),
                'url' => route('admin.dashboard'),
                'icon' => 'heroicon-o-home',
            ])
        );

        return $this;
    }

    private function addRepositories(): self
    {
        $this->menu->addIfCan(
            PermissionEnum::REPOSITORIES_MANAGE->value,
            View::create($this->iconView, [
                'label' => __('repos.title'),
                'url' => route('admin.repositories.index'),
                'icon' => 'css-git-branch',
            ])
        );

        return $this;
    }

    private function addUserManagement(): self
    {
        $rolePermissions = [
            PermissionEnum::ROLES_CREATE->value, PermissionEnum::ROLES_EDIT->value, PermissionEnum::ROLES_DELETE->value,
        ];
        $userPermissions = [
            PermissionEnum::USERS_CREATE->value, PermissionEnum::USERS_EDIT->value, PermissionEnum::USERS_DELETE->value,
        ];

        $this->menu->submenuIf(
            $this->user->canAny([...$rolePermissions, ...$userPermissions]),
            View::create($this->submenuView, [
                'label' => __('base::users.user_management'),
                'icon' => 'heroicon-o-users',
            ]),
            function (Menu $menu) use ($rolePermissions, $userPermissions) {
                return $menu->expandable(function (Menu $menu) use ($rolePermissions, $userPermissions) {
                    $menu->viewIf($this->user->canAny($rolePermissions), $this->submenuItemView, [
                        'label' => __('base::roles.index.title'),
                        'url' => route('admin.roles.index'),
                    ])
                        ->viewIf($this->user->canAny($userPermissions), $this->submenuItemView, [
                            'label' => __('base::users.index.title'),
                            'url' => route('admin.users.index'),
                        ]);
                });
            }
        );

        return $this;
    }
}
