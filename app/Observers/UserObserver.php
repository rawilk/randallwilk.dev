<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class UserObserver
{
    public function updating(User $user)
    {
        // Double check to ensure a user has permission to edit the given user.
        if (Auth::check() && ! Auth::user()->can('edit', $user)) {
            return false;
        }
    }

    public function deleting(User $user)
    {
        // Double checking if user is allowed to delete the user here for scenarios
        // such as "bulk deletes".
        if (Auth::check() && ! Auth::user()->can('delete', $user)) {
            return false;
        }
    }
}
