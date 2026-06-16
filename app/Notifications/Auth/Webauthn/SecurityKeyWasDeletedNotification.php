<?php

declare(strict_types=1);

namespace App\Notifications\Auth\Webauthn;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;

class SecurityKeyWasDeletedNotification extends AccountSecurityNotification
{
    protected string $keyName = '';

    public function setKeyName(string $name): static
    {
        $this->keyName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.security-key-deleted.greeting');

        $name = e($this->keyName);
        $supportEmail = AppConfig::supportEmail();

        $lines = __('notifications/auth/security.security-key-deleted.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'name' => $name,
                'support' => $supportEmail,
            ]));
        }
    }
}
