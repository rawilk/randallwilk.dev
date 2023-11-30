<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users\Concerns;

use App\Enums\PermissionEnum;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Role;

/**
 * @property-read bool $canAssignPermissions
 * @property-read bool $canAssignRoles
 * @property-read $permissionModel
 * @property-read $roleModel
 */
trait SavesUserAbilities
{
    public $rolePermissions = [];

    public $userPermissions = [];

    public $currentlySelectedPermissions = [];

    public $userRoles = [];

    public $roles = [];

    public function getCanAssignPermissionsProperty(): bool
    {
        if (property_exists($this, 'user') && $this->user->getKey()) {
            return Auth::user()->can('assignPermissionsTo', $this->user);
        }

        return Auth::user()->can(PermissionEnum::PERMISSIONS_ASSIGN->value);
    }

    public function getCanAssignRolesProperty(): bool
    {
        if (property_exists($this, 'user') && $this->user->getKey()) {
            return Auth::user()->can('assignRolesTo', $this->user);
        }

        return Auth::user()->can(PermissionEnum::ROLES_ASSIGN->value);
    }

    public function getPermissionModelProperty()
    {
        return app(config('permission.models.permission'));
    }

    public function getRoleModelProperty()
    {
        return app(config('permission.models.role'));
    }

    public function mountSavesUserAbilities(): void
    {
        if ($this->canAssignRoles) {
            $this->roles = $this->roleModel::query()
                ->orderBy('name')
                ->with('permissions:id')
                ->get(['id', 'name', 'description'])
                ->transform(function (Role $role) {
                    $role->mapped_permissions = array_map(
                        'strval',
                        $role->permissions->pluck('id')->toArray()
                    );

                    return $role;
                });

            $this->userRoles = $this->getCurrentRoles();
        }

        if ($this->canAssignPermissions) {
            $this->userPermissions = $this->directPermissions();
            $this->rolePermissions = $this->permissionsViaRoles();

            $this->currentlySelectedPermissions = array_merge($this->userPermissions, $this->rolePermissions);
        }
    }

    private function getCurrentRoles(): array
    {
        if (! property_exists($this, 'user') || ! $this->user->getKey()) {
            return [];
        }

        return array_map(
            'strval',
            $this->user->roles->pluck('id')->toArray(),
        );
    }

    private function directPermissions(): array
    {
        if (! property_exists($this, 'user') || ! $this->user->getKey()) {
            return [];
        }

        return array_map(
            'strval',
            $this->user->getDirectPermissions()->pluck('id')->toArray(),
        );
    }

    private function permissionsViaRoles(): array
    {
        if (! $this->canAssignRoles) {
            return array_map(
                'strval',
                $this->user?->getPermissionsViaRoles()->pluck('id')->toArray() ?? [],
            );
        }

        if (! count($this->userRoles)) {
            return [];
        }

        return $this->roleModel::query()
            ->whereKey($this->userRoles)
            ->get(['id'])
            ->map(fn (Role $role) => $role->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray())
            ->flatten()
            ->unique()
            ->toArray();
    }
}
