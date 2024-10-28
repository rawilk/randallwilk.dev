<?php

declare(strict_types=1);

namespace App\Listeners\Auth\Passkeys;

use App\Enums\UserSetting;
use App\Models\User;
use App\Notifications\Auth\Passkeys\PasskeyDeletedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Events\Passkeys\PasskeyDeleted;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class PasskeyDeletedListener
{
    use SetsDeviceDetails;

    public function handle(PasskeyDeleted $event): void
    {
        $notification = new PasskeyDeletedNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setKeyName($event->passkey->name);

        $this->setDeviceDetailsOn($notification, $event->user);

        $event->user->notify($notification);

        $this->updatePreferredMfaMethod($event->user);
    }

    protected function getProfileUrl(): ?string
    {
        return rescue(
            callback: fn (): string => Security::getUrl(panel: filament()->getId()),
            report: false,
        );
    }

    protected function updatePreferredMfaMethod(User $user): void
    {
        if ($user->settings()->get(UserSetting::PreferredMfaMethod) !== MfaChallengeMode::Webauthn->value) {
            return;
        }

        if ($user->webauthnKeys()->exists()) {
            return;
        }

        $user->settings()->forget(UserSetting::PreferredMfaMethod);
    }
}
