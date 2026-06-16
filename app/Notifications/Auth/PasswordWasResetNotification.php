<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;

class PasswordWasResetNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.password-was-reset.greeting');

        $supportEmail = AppConfig::supportEmail();

        $lines = __('notifications/auth/security.password-was-reset.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'support' => $supportEmail,
            ]));
        }
    }
}
