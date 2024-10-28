<?php

declare(strict_types=1);

namespace App\Notifications\Auth\ConnectedAccounts;

use App\Notifications\Users\AccountSecurityNotification;

class GitHubDisconnectedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.github_disconnected.greeting');

        $this->markdownLine(__('notifications/auth/security.github_disconnected.line1'));
        $this->markdownLine(__('notifications/auth/security.github_disconnected.line2'));
    }
}
