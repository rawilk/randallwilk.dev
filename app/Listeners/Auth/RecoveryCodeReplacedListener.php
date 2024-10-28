<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\RecoveryCodeReplacedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Events\RecoveryCodeReplaced;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class RecoveryCodeReplacedListener
{
    use SetsDeviceDetails;

    public function handle(RecoveryCodeReplaced $event): void
    {
        $notification = new RecoveryCodeReplacedNotification;
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
