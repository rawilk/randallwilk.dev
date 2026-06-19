<?php

declare(strict_types=1);

namespace App\Listeners\Auth\AuthenticatorApps;

use App\Notifications\Auth\AuthenticatorApps\AuthenticatorAppWasDeletedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\App\Events\AuthenticatorAppWasDeleted;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class AuthenticatorAppWasDeletedListener
{
    use SetsDeviceDetails;

    public function handle(AuthenticatorAppWasDeleted $event): void
    {
        $notification = new AuthenticatorAppWasDeletedNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setAuthenticatorAppName($event->authenticatorApp->name);

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
