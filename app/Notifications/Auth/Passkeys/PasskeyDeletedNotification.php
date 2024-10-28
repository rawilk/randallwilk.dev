<?php

declare(strict_types=1);

namespace App\Notifications\Auth\Passkeys;

use App\Notifications\Users\AccountSecurityNotification;

class PasskeyDeletedNotification extends AccountSecurityNotification
{
    protected string $keyName;

    public function setKeyName(string $name): static
    {
        $this->keyName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.passkey_deleted.greeting');

        $this->markdownLine(__('notifications/auth/security.passkey_deleted.line1', ['name' => e($this->keyName)]));
        $this->markdownLine(__('notifications/auth/security.passkey_deleted.line2'));
        $this->markdownLine(__('notifications/auth/security.passkey_deleted.line3'));
    }
}
