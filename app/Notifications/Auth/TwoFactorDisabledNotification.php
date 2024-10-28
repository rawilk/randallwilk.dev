<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;

class TwoFactorDisabledNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.two_factor_disabled.greeting');

        $this->line(__('notifications/auth/security.two_factor_disabled.line1'));
        $this->line(__('notifications/auth/security.two_factor_disabled.line2'));
        $this->markdownLine(__('notifications/auth/security.two_factor_disabled.line3', ['support' => config('randallwilk.support_email')]));
    }
}
