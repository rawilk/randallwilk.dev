<?php

declare(strict_types=1);

namespace App\Notifications\Auth\AuthenticatorApps;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;

class AuthenticatorAppWasDeletedNotification extends AccountSecurityNotification
{
    protected string $authenticatorAppName = '';

    public function setAuthenticatorAppName(string $name): static
    {
        $this->authenticatorAppName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.authenticator-app-deleted.greeting');

        $appName = e($this->authenticatorAppName);
        $supportEmail = AppConfig::supportEmail();

        $lines = __('notifications/auth/security.authenticator-app-deleted.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'name' => $appName,
                'support' => $supportEmail,
            ]));
        }
    }
}
