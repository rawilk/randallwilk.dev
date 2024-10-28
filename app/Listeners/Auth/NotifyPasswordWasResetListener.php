<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\PasswordWasResetNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Illuminate\Auth\Events\PasswordReset;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class NotifyPasswordWasResetListener
{
    use SetsDeviceDetails;

    public function handle(PasswordReset $event): void
    {
        $notification = new PasswordWasResetNotification;
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
