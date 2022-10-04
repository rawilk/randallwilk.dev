<?php

declare(strict_types=1);

namespace App\Listeners\TwoFactorAuth;

use App\Notifications\Users\NewAuthenticatorAppNotification;
use Rawilk\LaravelBase\Actions\TwoFactor\MarkTwoFactorEnabledAction;
use Rawilk\LaravelBase\Events\TwoFactorAuth\TwoFactorAuthEnabled;

final class TwoFactorAuthEnabledListener
{
    public function handle(TwoFactorAuthEnabled $event): void
    {
        app(MarkTwoFactorEnabledAction::class)($event->user);

        $event->user->notify(new NewAuthenticatorAppNotification);
    }
}
