<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;

class UserPasswordUpdatedNotification extends AccountSecurityNotification
{
    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.password_updated.greeting');

        $this->line(__('notifications/auth/security.password_updated.line1'));

        $this->line(
            str(__('notifications/auth/security.password_updated.line2', ['support' => config('randallwilk.support_email')]))
                ->inlineMarkdown()
                ->toHtmlString()
        );
    }
}
