<?php

declare(strict_types=1);

namespace App\Listeners\Auth\Webauthn;

use App\Enums\UserSetting;
use App\Models\User;
use App\Notifications\Auth\Webauthn\WebauthnKeyDeletedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Events\Webauthn\WebauthnKeyDeleted;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class WebauthnKeyDeletedListener
{
    use SetsDeviceDetails;

    public function handle(WebauthnKeyDeleted $event): void
    {
        $notification = new WebauthnKeyDeletedNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setKeyName($event->webauthnKey->name);

        $this->setDeviceDetailsOn($notification);

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
