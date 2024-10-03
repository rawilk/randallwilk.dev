<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;

final class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::USERS_CREATE->value);
    }

    public function edit(User $user, User $other): bool
    {
        if ($user->is($other)) {
            return true;
        }

        // Only super admins can edit other super admins...
        if ($other->isSuperAdmin() && ! $user->isSuperAdmin()) {
            return false;
        }

        return $user->hasPermissionTo(PermissionEnum::USERS_EDIT->value);
    }

    public function delete(User $user, User $other): bool
    {
        // You should not be able to delete your own user account if you are impersonating someone...
        if ($this->isOriginalImpersonator($other)) {
            return false;
        }

        return $user->isNot($other)
            && $user->hasPermissionTo(PermissionEnum::USERS_DELETE->value);
    }

    public function assignPermissionsTo(User $user, User $other): bool
    {
        // A super admin has no need to have direct permissions...
        if ($other->isSuperAdmin()) {
            return false;
        }

        return $this->edit($user, $other)
            && $user->hasPermissionTo(PermissionEnum::PERMISSIONS_ASSIGN->value);
    }

    public function assignRolesTo(User $user, User $other): bool
    {
        return $this->edit($user, $other)
            && $user->hasPermissionTo(PermissionEnum::ROLES_ASSIGN->value);
    }

    public function impersonate(User $user, User $other): bool
    {
        // Only super admins can impersonate other super admins...
        if ($other->isSuperAdmin() && ! $user->isSuperAdmin()) {
            return false;
        }

        // You shouldn't be able to impersonate yourself if you are impersonating
        // another user account, and then try to impersonate yourself...
        if ($this->isOriginalImpersonator($other)) {
            return false;
        }

        return $user->hasPermissionTo(PermissionEnum::USERS_IMPERSONATE->value)
            && $user->isNot($other);
    }

    private function isOriginalImpersonator(User $user): bool
    {
        if (! isImpersonating()) {
            return false;
        }

        return $user->getKey() === (int) app(ImpersonatesUsers::class)->impersonatorId(request());
    }
}
