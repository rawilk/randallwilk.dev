<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;

class PasswordWasResetNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.password_was_reset.greeting');

        $this->line(__('notifications/auth/security.password_was_reset.line1'));
        $this->line(__('notifications/auth/security.password_was_reset.line2'));

        $this->line(
            str(__('notifications/auth/security.password_was_reset.line3', ['support' => config('randallwilk.support_email')]))
                ->inlineMarkdown()
                ->toHtmlString()
        );
    }
}
