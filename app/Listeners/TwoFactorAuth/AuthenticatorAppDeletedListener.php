<?php

declare(strict_types=1);

namespace App\Listeners\TwoFactorAuth;

use App\Models\User\User;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\TwoFactorAuth\AuthenticatorAppWasDeleted;
use Rawilk\LaravelBase\Features;
use Rawilk\Webauthn\Facades\Webauthn;

final class AuthenticatorAppDeletedListener
{
    /**
     * Mark a user's 2fa status as inactive if they don't have any other authenticator apps
     * or security keys registered. Also remove recovery codes as they are not needed anymore.
     *
     * @param  \Rawilk\LaravelBase\Events\TwoFactorAuth\AuthenticatorAppWasDeleted  $event
     * @return void
     */
    public function handle(AuthenticatorAppWasDeleted $event): void
    {
        if ($this->hasOtherAppsRegistered($event->authenticatorApp)) {
            return;
        }

        $user = $event->authenticatorApp->user;

        if (! $user) {
            return;
        }

        if ($this->hasOtherTwoFactorEnabled($user)) {
            return;
        }

        $user->forceFill([
            'two_factor_recovery_codes' => null,
            'two_factor_enabled' => false,
        ]);
    }

    private function hasOtherAppsRegistered(AuthenticatorApp $authenticatorApp): bool
    {
        return app(AuthenticatorApp::class)::query()
            ->where('id', '!=', $authenticatorApp->getKey())
            ->where('user_id', $authenticatorApp->user_id)
            ->exists();
    }

    private function hasOtherTwoFactorEnabled(User $user): bool
    {
        if (! Features::canManageWebauthnAuthentication()) {
            return false;
        }

        return Webauthn::enabledFor($user);
    }
}
