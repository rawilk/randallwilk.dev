<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Enums\UserSetting;
use App\Notifications\Auth\MultiFactorDisabledNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\Events\MultiFactorAuthenticationWasDisabled;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class MultiFactorAuthenticationDisabledListener
{
    use SetsDeviceDetails;

    public function handle(MultiFactorAuthenticationWasDisabled $event): void
    {
        $notification = new MultiFactorDisabledNotification;
        $notification->setUrl($this->getProfileUrl());
        $notification->delay(now()->addSeconds(30));

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
