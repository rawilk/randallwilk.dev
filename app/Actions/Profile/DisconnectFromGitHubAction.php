<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;

final class DisconnectFromGitHubAction
{
    public function __invoke(User $user): void
    {
        $user->forceFill([
            'github_id' => null,
            'github_username' => null,
        ])->save();
    }
}
