<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;

class RecoveryCodesRegeneratedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.recovery_codes_regenerated.greeting');

        $this->line(__('notifications/auth/security.recovery_codes_regenerated.line1'));
        $this->markdownLine(__('notifications/auth/security.recovery_codes_regenerated.line2'));
        $this->line(__('notifications/auth/security.recovery_codes_regenerated.line3'));
        $this->markdownLine(__('notifications/auth/security.recovery_codes_regenerated.line4', ['support' => config('randallwilk.support_email')]));
    }
}
