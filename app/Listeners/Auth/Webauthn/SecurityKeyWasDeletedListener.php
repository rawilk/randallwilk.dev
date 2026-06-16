<?php

declare(strict_types=1);

namespace App\Listeners\Auth\Webauthn;

use App\Notifications\Auth\Webauthn\SecurityKeyWasDeletedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Events\SecurityKeyWasDeleted;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class SecurityKeyWasDeletedListener
{
    use SetsDeviceDetails;

    public function handle(SecurityKeyWasDeleted $event): void
    {
        $notification = new SecurityKeyWasDeletedNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setKeyName($event->webauthnKey->name);

        $this->setDeviceDetailsOn($notification);

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
