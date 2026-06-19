<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Notifications\Auth\RecoveryCodeWasUsedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Events\RecoveryCodeWasUsed;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class RecoveryCodeWasUsedListener
{
    use SetsDeviceDetails;

    public function handle(RecoveryCodeWasUsed $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        $notification = new RecoveryCodeWasUsedNotification;
        $notification->setUrl($this->getProfileUrl());
        $notification->setCodesRemaining(
            count($user->getAuthenticationRecoveryCodes() ?? []),
        );

        $this->setDeviceDetailsOn($notification, $user);

        $user->notify($notification);
    }

    protected function getProfileUrl(): ?string
    {
        return rescue(
            callback: fn (): string => Security::getUrl(panel: filament()->getId()),
            report: false,
        );
    }
}
