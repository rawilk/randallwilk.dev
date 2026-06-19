<?php

declare(strict_types=1);

namespace App\Listeners\Auth\Webauthn;

use App\Notifications\Auth\Webauthn\SecurityKeyWasCreatedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Events\SecurityKeyWasCreated;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class SecurityKeyWasCreatedListener
{
    use SetsDeviceDetails;

    public function handle(SecurityKeyWasCreated $event): void
    {
        $notification = new SecurityKeyWasCreatedNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setKeyName($event->webauthnKey->name);

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
