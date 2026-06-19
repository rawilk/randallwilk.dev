<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\RecoveryCodesRegeneratedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Events\RecoveryCodesWereRegenerated;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class RecoveryCodesRegeneratedListener
{
    use SetsDeviceDetails;

    public function handle(RecoveryCodesWereRegenerated $event): void
    {
        $notification = new RecoveryCodesRegeneratedNotification;
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
