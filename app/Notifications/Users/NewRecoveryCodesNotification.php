<?php

declare(strict_types=1);

namespace App\Notifications\Users;

final class NewRecoveryCodesNotification extends AccountSecurityNotification
{
    protected function greeting(): string
    {
        return __('New backup codes generated for 2-Step Verification');
    }

    protected function line1(): string
    {
        return __('If you didn\'t regenerate your backup codes, someone might be using your account. Check and secure your account now.');
    }
}
