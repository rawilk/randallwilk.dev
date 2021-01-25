<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

final class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function edit(User $user, User $other): bool
    {
        // A user can always edit their own account.
        if ($user->is($other)) {
            return true;
        }
        
        return $user->is_admin;
    }

    public function delete(User $user, User $other): bool
    {
        if (! $user->is_admin) {
            return false;
        }

        // You shouldn't be deleting yourself from the admin panel...
        return $user->isNot($other);
    }

    public function impersonate(User $user, User $other): bool
    {
        // You shouldn't be able to impersonate yourself if you are impersonating
        // another user account, and then try to impersonate yourself...
        if ($this->isOriginalImpersonator($other)) {
            return false;
        }

        return $user->is_admin && $user->isNot($other);
    }

    private function isOriginalImpersonator(User $user): bool
    {
        if (! isImpersonating()) {
            return false;
        }

        return (int) Session::get('impersonate') === $user->id;
    }
}
