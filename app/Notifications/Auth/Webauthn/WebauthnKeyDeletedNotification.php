<?php

declare(strict_types=1);

namespace App\Notifications\Auth\Webauthn;

use App\Notifications\Users\AccountSecurityNotification;

class WebauthnKeyDeletedNotification extends AccountSecurityNotification
{
    protected string $keyName = '';

    public function setKeyName(string $name): static
    {
        $this->keyName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.webauthn_key_deleted.greeting');

        $this->markdownLine(__('notifications/auth/security.webauthn_key_deleted.line1', ['name' => e($this->keyName)]));
        $this->markdownLine(__('notifications/auth/security.webauthn_key_deleted.line2'));
    }
}
