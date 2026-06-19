<?php

declare(strict_types=1);

namespace App\Listeners\Auth\AuthenticatorApps;

use App\Notifications\Auth\AuthenticatorApps\AuthenticatorAppWasCreatedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\App\Events\AuthenticatorAppWasCreated;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class AuthenticatorAppWasCreatedListener
{
    use SetsDeviceDetails;

    public function handle(AuthenticatorAppWasCreated $event): void
    {
        $notification = new AuthenticatorAppWasCreatedNotification;
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
