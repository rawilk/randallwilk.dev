<?php

declare(strict_types=1);

namespace App\Listeners\Webauthn;

use App\Notifications\Users\NewWebauthnKeyNotification;
use Rawilk\LaravelBase\Actions\TwoFactor\MarkTwoFactorEnabledAction;
use Rawilk\Webauthn\Events\WebauthnKeyWasRegistered;

final class WebauthnKeyRegisteredListener
{
    public function handle(WebauthnKeyWasRegistered $event): void
    {
        /** @var \Illuminate\Notifications\Notifiable|\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $event->webauthnKey->user;

        if (! $user) {
            return;
        }

        app(MarkTwoFactorEnabledAction::class)($user);

        $user->notify(new NewWebauthnKeyNotification);
    }
}
