<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\GitHub\Repository;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class RepositoryPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Repository $repository): bool
    {
        return $user->hasPermissionTo(PermissionEnum::REPOSITORIES_MANAGE->value);
    }

    public function edit(User $user, Repository $repository): bool
    {
        return $user->hasPermissionTo(PermissionEnum::REPOSITORIES_MANAGE->value);
    }
}
