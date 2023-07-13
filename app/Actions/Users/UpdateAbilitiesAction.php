<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\Access\Role;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class UpdateAbilitiesAction
{
    private bool $canAssignPermissions;

    private bool $canAssignRoles;

    private array $data;

    private Collection $roles;

    private User $authenticatedUser;

    public function __invoke(User $user, array $input, User $authenticatedUser = null, bool $skipValidation = false): void
    {
        $this->init($user, $input, $authenticatedUser);

        $this->setData($input, $skipValidation);

        if ($this->canAssignRoles && is_array($this->data['roles'] ?? null)) {
            $this->assignRoles($user);
        }

        if ($this->canAssignPermissions && is_array($this->data['permissions'] ?? null)) {
            $this->assignPermissions($user);
        }

        if ($user->wasRecentlyCreated && ! $user->roles->count()) {
            $user->assignRole(
                Role::$userName,
            );
        }
    }

    private function init(User $user, array $input, User $authenticatedUser = null): void
    {
        $this->authenticatedUser = $authenticatedUser ?: Auth::user();

        $this->canAssignPermissions = $this->authenticatedUser->can('assignPermissionsTo', $user);
        $this->canAssignRoles = $this->authenticatedUser->can('assignRolesTo', $user);

        $this->roles = $this->canAssignRoles && is_array($input['roles'] ?? null)
            ? app(config('permission.models.role'))::whereKey($input['roles'])->orWhereIn('name', $input['roles'])->get(['id', 'name'])
            : $user->roles;
    }

    private function assignPermissions(User $user): void
    {
        $user->syncPermissions($this->syncablePermissions());
    }

    private function assignRoles(User $user): void
    {
        $user->syncRoles($this->syncableRoles());
    }

    private function syncablePermissions(): array
    {
        if ($this->hasSuperAdminRole()) {
            return [];
        }

        $rolePermissions = $this
            ->roles
            ->map(fn (Role $role) => $role->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray())
            ->flatten()
            ->unique();

        return collect($this->data['permissions'] ?? [])
            ->diff($rolePermissions)
            ->values()
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }

    private function syncableRoles(): array
    {
        // We only want super admins to be able to assign the super admin role...
        return $this
            ->roles
            ->when(! $this->authenticatedUser->isSuperAdmin(), fn ($roles) => $roles->where('name', '!=', Role::$superAdminName))
            ->pluck('id')
            ->toArray();
    }

    private function hasSuperAdminRole(): bool
    {
        return $this->authenticatedUser->isSuperAdmin() && $this->roles->contains('name', Role::$superAdminName);
    }

    private function setData(array $input, bool $skipValidation): void
    {
        // Useful for when we are importing from csv.
        if ($skipValidation) {
            $this->data = $input;

            return;
        }

        $this->data = Validator::make($input, [
            'permissions' => [
                Rule::when($this->canAssignPermissions, ['array'], ['nullable']),
            ],
            'roles' => [
                Rule::when($this->canAssignRoles, ['array'], ['nullable']),
            ],
        ])->validate();
    }
}
