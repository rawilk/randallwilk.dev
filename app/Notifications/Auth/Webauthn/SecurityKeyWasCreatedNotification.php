<?php

declare(strict_types=1);

namespace App\Notifications\Auth\Webauthn;

use App\Notifications\Users\AccountSecurityNotification;

class SecurityKeyWasCreatedNotification extends AccountSecurityNotification
{
    protected string $keyName = '';

    public function setKeyName(string $name): static
    {
        $this->keyName = $name;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.security-key-created.greeting');

        $name = e($this->keyName);

        $lines = __('notifications/auth/security.security-key-created.lines') ?? [];

        foreach ($lines as $line) {
            $this->markdownLine(__($line, [
                'name' => $name,
            ]));
        }
    }
}
