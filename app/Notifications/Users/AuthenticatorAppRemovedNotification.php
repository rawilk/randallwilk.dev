<?php

declare(strict_types=1);

namespace App\Notifications\Users;

final class AuthenticatorAppRemovedNotification extends AccountSecurityNotification
{
    protected function greeting(): string
    {
        return __('Authenticator app removed from 2-Step Verification');
    }

    protected function line1(): string
    {
        return __('If you didn\'t remove an authenticator app, someone might be using your account. Check and secure your account now.');
    }
}
