<?php

declare(strict_types=1);

namespace App\Listeners\Auth\AuthenticatorApps;

use App\Enums\UserSetting;
use App\Models\User;
use App\Notifications\Auth\AuthenticatorApps\TwoFactorAppDeletedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Events\AuthenticatorApps\TwoFactorAppRemoved;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class TwoFactorAppDeletedListener
{
    use SetsDeviceDetails;

    public function handle(TwoFactorAppRemoved $event): void
    {
        $notification = new TwoFactorAppDeletedNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setAuthenticatorAppName($event->authenticatorApp->name);

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
        if ($user->settings()->get(UserSetting::PreferredMfaMethod) !== MfaChallengeMode::App->value) {
            return;
        }

        if ($user->authenticatorApps()->exists()) {
            return;
        }

        $user->settings()->forget(UserSetting::PreferredMfaMethod);
    }
}
