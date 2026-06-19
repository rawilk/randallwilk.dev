<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;

class MultiFactorEnabledNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.multi-factor-enabled.greeting');

        $domain = AppConfig::appDomain();
        $supportEmail = AppConfig::supportEmail();

        $lines = __('notifications/auth/security.multi-factor-enabled.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'domain' => $domain,
                'support' => $supportEmail,
            ]));
        }
    }
}
