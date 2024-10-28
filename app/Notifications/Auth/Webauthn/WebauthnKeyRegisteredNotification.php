<?php

declare(strict_types=1);

namespace App\Notifications\Auth\Webauthn;

use App\Notifications\Users\AccountSecurityNotification;

class WebauthnKeyRegisteredNotification extends AccountSecurityNotification
{
    protected string $keyName = '';

    public function setKeyName(string $name): static
    {
        $this->keyName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.webauthn_key_registered.greeting');

        $this->markdownLine(__('notifications/auth/security.webauthn_key_registered.line1', ['name' => e($this->keyName)]));
        $this->markdownLine(__('notifications/auth/security.webauthn_key_registered.line2'));
    }
}
