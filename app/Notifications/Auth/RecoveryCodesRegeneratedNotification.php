<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;

class RecoveryCodesRegeneratedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.recovery-codes-regenerated.greeting');

        $supportEmail = AppConfig::supportEmail();

        $lines = __('notifications/auth/security.recovery-codes-regenerated.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'support' => $supportEmail,
            ]));
        }
    }
}
