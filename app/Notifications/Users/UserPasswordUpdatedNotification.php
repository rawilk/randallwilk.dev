<?php

declare(strict_types=1);

namespace App\Notifications\Users;

final class UserPasswordUpdatedNotification extends AccountSecurityNotification
{
    protected function greeting(): string
    {
        return __('Account password was updated');
    }

    protected function line1(): string
    {
        return __('If you didn\'t request or initiate a change to your account\'s password, someone might be using your account. Check and secure your account now.');
    }
}
