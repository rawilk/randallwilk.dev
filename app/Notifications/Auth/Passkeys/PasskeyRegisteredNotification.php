<?php

declare(strict_types=1);

namespace App\Notifications\Auth\Passkeys;

use App\Notifications\Users\AccountSecurityNotification;

class PasskeyRegisteredNotification extends AccountSecurityNotification
{
    protected string $keyName;

    public function setKeyName(string $name): static
    {
        $this->keyName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.passkey_registered.greeting');

        $this->markdownLine(__('notifications/auth/security.passkey_registered.line1', ['name' => e($this->keyName)]));
        $this->markdownLine(__('notifications/auth/security.passkey_registered.line2', ['url' => $this->getUrl()]));
        $this->markdownLine(__('notifications/auth/security.passkey_registered.line3'));
    }
}
