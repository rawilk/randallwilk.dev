<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use const PHP_URL_HOST;

use App\Notifications\Users\AccountSecurityNotification;

class TwoFactorEnabledNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.two_factor_enabled.greeting');

        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        $this->line(__('notifications/auth/security.two_factor_enabled.line1', ['domain' => $domain]));
        $this->line(__('notifications/auth/security.two_factor_enabled.line2'));
        $this->markdownLine(__('notifications/auth/security.two_factor_enabled.line3', ['support' => config('randallwilk.support_email')]));
    }
}
