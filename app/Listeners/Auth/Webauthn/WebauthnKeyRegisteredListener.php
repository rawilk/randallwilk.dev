<?php

declare(strict_types=1);

namespace App\Listeners\Auth\Webauthn;

use App\Enums\UserSetting;
use App\Models\User;
use App\Notifications\Auth\Webauthn\WebauthnKeyRegisteredNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Events\Webauthn\WebauthnKeyRegistered;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;

readonly class WebauthnKeyRegisteredListener
{
    use SetsDeviceDetails;

    public function handle(WebauthnKeyRegistered $event): void
    {
        $notification = new WebauthnKeyRegisteredNotification;
        $notification
            ->setUrl($this->getProfileUrl())
            ->setKeyName($event->webauthnKey->name);

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
        if ($user->settings()->has(UserSetting::PreferredMfaMethod)) {
            return;
        }

        $user->settings()->set(UserSetting::PreferredMfaMethod, MfaChallengeMode::Webauthn->value);
    }
}
