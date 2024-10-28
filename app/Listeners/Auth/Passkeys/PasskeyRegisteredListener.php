<?php

declare(strict_types=1);

namespace App\Listeners\Auth\Passkeys;

use App\Enums\UserSetting;
use App\Models\User;
use App\Notifications\Auth\Passkeys\PasskeyRegisteredNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Events\Passkeys\PasskeyRegistered;
use Rawilk\ProfileFilament\Events\Webauthn\WebauthnKeyUpgradeToPasskey;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class PasskeyRegisteredListener
{
    use SetsDeviceDetails;

    public function handle(PasskeyRegistered|WebauthnKeyUpgradeToPasskey $event): void
    {
        $notification = new PasskeyRegisteredNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setKeyName($event->passkey->name);

        $this->setDeviceDetailsOn($notification, $event->user);

        $event->user->notify($notification);

        $this->updatePreferredMfaMethod($event->user);
    }

    protected function getProfileUrl(): ?string
    {
        return rescue(
            callback: fn (): string => Security::getUrl(panel: filament()->getId()),
            report: false,
        );
    }

    protected function updatePreferredMfaMethod(User $user): void
    {
        if ($user->settings()->has(UserSetting::PreferredMfaMethod)) {
            return;
        }

        $user->settings()->set(UserSetting::PreferredMfaMethod, MfaChallengeMode::Webauthn->value);
    }
}
