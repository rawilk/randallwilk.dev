<?php

declare(strict_types=1);

namespace App\Listeners\Auth\AuthenticatorApps;

use App\Enums\UserSetting;
use App\Models\User;
use App\Notifications\Auth\AuthenticatorApps\TwoFactorAppAddedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Events\AuthenticatorApps\TwoFactorAppAdded;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class TwoFactorAppAddedListener
{
    use SetsDeviceDetails;

    public function handle(TwoFactorAppAdded $event): void
    {
        $notification = new TwoFactorAppAddedNotification;
        $notification->setUrl($this->getProfileUrl());

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

        $user->settings()->set(UserSetting::PreferredMfaMethod, MfaChallengeMode::App->value);
    }
}
