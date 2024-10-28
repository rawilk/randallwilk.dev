<?php

declare(strict_types=1);

namespace App\Notifications\Auth\AuthenticatorApps;

use App\Notifications\Users\AccountSecurityNotification;

class TwoFactorAppAddedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.two_factor_app_added.greeting');

        $this->line(__('notifications/auth/security.two_factor_app_added.line1'));
    }
}
