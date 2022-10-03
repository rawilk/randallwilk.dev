<?php

declare(strict_types=1);

namespace App\Listeners\TwoFactorAuth;

use App\Notifications\Users\AuthenticatorAppRemovedNotification;
use Rawilk\LaravelBase\Events\TwoFactorAuth\AuthenticatorAppWasDeleted;

final class NotifyAuthenticatorAppDeletedListener
{
    public function handle(AuthenticatorAppWasDeleted $event): void
    {
        if (! $event->authenticatorApp->user) {
            return;
        }

        $event->authenticatorApp->user->notify(new AuthenticatorAppRemovedNotification);
    }
}
