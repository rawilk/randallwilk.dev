<?php

declare(strict_types=1);

namespace App\Notifications\Auth\ConnectedAccounts;

use App\Notifications\Users\AccountSecurityNotification;

class GitHubConnectedNotification extends AccountSecurityNotification
{
    protected string $username = '';

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.github_connected.greeting');

        $this->markdownLine(__('notifications/auth/security.github_connected.line1', ['username' => e($this->username)]));
        $this->markdownLine(__('notifications/auth/security.github_connected.line2'));
    }
}
