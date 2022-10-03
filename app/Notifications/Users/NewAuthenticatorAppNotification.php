<?php

declare(strict_types=1);

namespace App\Notifications\Users;

final class NewAuthenticatorAppNotification extends AccountSecurityNotification
{
    protected function greeting(): string
    {
        return __('Authenticator app added for 2-Step Verification');
    }

    protected function line1(): string
    {
        return __('If you didn\'t register an authenticator app, someone might be using your account. Check and secure your account now.');
    }
}
