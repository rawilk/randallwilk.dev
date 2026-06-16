<?php

declare(strict_types=1);

namespace App\Notifications\Auth\AuthenticatorApps;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;

class AuthenticatorAppWasCreatedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.authenticator-app-added.greeting');

        $supportEmail = AppConfig::supportEmail();

        $lines = __('notifications/auth/security.authenticator-app-added.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'support' => $supportEmail,
            ]));
        }
    }
}
