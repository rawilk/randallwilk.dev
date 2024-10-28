<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;

class RecoveryCodeReplacedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.recovery_code_replaced.greeting');

        $this->line(__('notifications/auth/security.recovery_code_replaced.line1'));
        $this->line(__('notifications/auth/security.recovery_code_replaced.line2'));

        $this->line(
            str(__('notifications/auth/security.recovery_code_replaced.line3', ['support' => config('randallwilk.support_email')]))
                ->inlineMarkdown()
                ->toHtmlString()
        );
    }
}
