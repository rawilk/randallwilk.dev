<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
    }

    public function update(User $user, User $other)
    {
        if ($user->is($other)) {
            return Response::allow();
        }
    }

    public function delete(User $user, User $other): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        return $user->isNot($other)
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, User $other)
    {
    }

    public function viewAny(User $user)
    {
    }
}
