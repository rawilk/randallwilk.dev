<?php

declare(strict_types=1);

namespace App\Listeners\Users;

use App\Notifications\Users\PasswordWasResetNotification;
use Illuminate\Auth\Events\PasswordReset;

final class NotifyPasswordWasReset
{
    public function handle(PasswordReset $event): void
    {
        $event->user->notify(new PasswordWasResetNotification);
    }
}
