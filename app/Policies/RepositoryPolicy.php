<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class RepositoryPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, Repository $repository): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Repository $repository): bool
    {
        return $user->is_admin;
    }
}
