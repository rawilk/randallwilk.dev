<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

class DeleteUserAction
{
    public function __invoke(User $user): void
    {
        $user->deleteAvatar();

        $user->delete();
    }
}
