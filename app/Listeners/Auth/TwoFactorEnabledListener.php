<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\TwoFactorEnabledNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Events\TwoFactorAuthenticationWasEnabled;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class TwoFactorEnabledListener
{
    use SetsDeviceDetails;

    public function handle(TwoFactorAuthenticationWasEnabled $event): void
    {
        $notification = new TwoFactorEnabledNotification;
        $notification->setUrl($this->getProfileUrl());

        $this->setDeviceDetailsOn($notification, $event->user);

        $event->user->notify($notification);
    }

    protected function getProfileUrl(): ?string
    {
        return rescue(
            callback: fn (): string => Security::getUrl(panel: filament()->getId()),
            report: false,
        );
    }
}
