<?php

declare(strict_types=1);

namespace App\Notifications\Auth\AuthenticatorApps;

use App\Notifications\Users\AccountSecurityNotification;

class TwoFactorAppDeletedNotification extends AccountSecurityNotification
{
    protected string $authenticatorAppName = '';

    public function setAuthenticatorAppName(string $name): static
    {
        $this->authenticatorAppName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.two_factor_app_removed.greeting');

        $this->markdownLine(__('notifications/auth/security.two_factor_app_removed.line1', ['name' => e($this->authenticatorAppName)]));
        $this->markdownLine(__('notifications/auth/security.two_factor_app_removed.line2'));
    }
}
