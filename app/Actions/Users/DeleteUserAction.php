<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

final class DeleteUserAction
{
    public function execute(User $user): void
    {
        // User avatar will be deleted in the user observer.
        $user->delete();
    }
}
