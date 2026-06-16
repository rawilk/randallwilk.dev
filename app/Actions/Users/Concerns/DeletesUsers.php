<?php

declare(strict_types=1);

namespace App\Actions\Users\Concerns;

use App\Models\User;

trait DeletesUsers
{
    protected function deleteUser(User $user): void
    {
        $user->deleteAvatar();

        $user->delete();
    }
}
