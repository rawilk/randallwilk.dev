<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\UserPasswordUpdatedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Events\UserPasswordWasUpdated;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class NotifyUserPasswordUpdatedListener
{
    use SetsDeviceDetails;

    public function handle(UserPasswordWasUpdated $event): void
    {
        $notification = new UserPasswordUpdatedNotification;
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
