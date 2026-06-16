<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

readonly class DeleteUserAction
{
    use Concerns\DeletesUsers;

    public function __invoke(User $user): void
    {
        $this->deleteUser($user);
    }
}
