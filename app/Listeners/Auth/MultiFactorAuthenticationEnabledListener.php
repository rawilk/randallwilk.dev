<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\MultiFactorEnabledNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\Events\MultiFactorAuthenticationWasEnabled;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class MultiFactorAuthenticationEnabledListener
{
    use SetsDeviceDetails;

    public function handle(MultiFactorAuthenticationWasEnabled $event): void
    {
        $notification = new MultiFactorEnabledNotification;
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
