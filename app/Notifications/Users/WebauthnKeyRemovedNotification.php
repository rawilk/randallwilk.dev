<?php

declare(strict_types=1);

namespace App\Notifications\Users;

final class WebauthnKeyRemovedNotification extends AccountSecurityNotification
{
    protected function greeting(): string
    {
        return __('Security key removed from 2-Step Verification');
    }

    protected function line1(): string
    {
        return __('If you didn\'t remove a security key, someone might be using your account. Check and secure your account now.');
    }
}
