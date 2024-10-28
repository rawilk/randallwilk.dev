<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Enums\UserSetting;
use App\Notifications\Auth\TwoFactorDisabledNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Events\TwoFactorAuthenticationWasDisabled;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class TwoFactorDisabledListener
{
    use SetsDeviceDetails;

    public function handle(TwoFactorAuthenticationWasDisabled $event): void
    {
        $notification = new TwoFactorDisabledNotification;
        $notification->setUrl($this->getProfileUrl());
        $notification->delay(now()->addSeconds(30));

        $this->setDeviceDetailsOn($notification, $event->user);

        $event->user->notify($notification);

        $event->user->settings()->forget(UserSetting::PreferredMfaMethod);
    }

    protected function getProfileUrl(): ?string
    {
        return rescue(
            callback: fn (): string => Security::getUrl(panel: filament()->getId()),
            report: false,
        );
    }
}
