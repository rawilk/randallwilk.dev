<?php

declare(strict_types=1);

namespace App\Notifications\Users;

final class NewWebauthnKeyNotification extends AccountSecurityNotification
{
    protected function greeting(): string
    {
        return __('Security key added for 2-Step Verification');
    }

    protected function line1(): string
    {
        return __('If you didn\'t add a security key, someone might be using your account. Check and secure your account now.');
    }
}
