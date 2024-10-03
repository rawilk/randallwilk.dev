<?php

declare(strict_types=1);

namespace App\Listeners\Webauthn;

use App\Models\User;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\Webauthn\WebauthnKeyWasDeleted;
use Rawilk\LaravelBase\Features;
use Rawilk\Webauthn\Contracts\WebauthnKey;

final class WebauthnKeyDeletedListener
{
    /**
     * Mark a user's 2fa status as inactive if they don't have any other security keys registered
     * or have an authenticator app registered. Also remove recovery codes as they are not needed
     * anymore.
     */
    public function handle(WebauthnKeyWasDeleted $event): void
    {
        if ($this->hasOtherSecurityKeysRegistered($event->webauthnKey)) {
            return;
        }

        /** @var \Illuminate\Contracts\Auth\Authenticatable|User $user */
        $user = $event->webauthnKey->user;

        if (! $user) {
            return;
        }

        if ($this->userHasOtherTwoFactorEnabled($user)) {
            return;
        }

        $user->forceFill([
            'two_factor_recovery_codes' => null,
            'two_factor_enabled' => false,
        ])->save();
    }

    private function hasOtherSecurityKeysRegistered(WebauthnKey $webauthnKey): bool
    {
        return app(WebauthnKey::class)::query()
            ->where('id', '!=', $webauthnKey->getKey())
            ->where('user_id', $webauthnKey->user_id)
            ->exists();
    }

    private function userHasOtherTwoFactorEnabled(User $user): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        return app(AuthenticatorApp::class)
            ->where('user_id', $user->getAuthIdentifier())
            ->exists();
    }
}
