<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Notifications\Users\AccountSecurityNotification;
use App\Support\AppConfig;
use Illuminate\Support\Facades\Lang;

class RecoveryCodeWasUsedNotification extends AccountSecurityNotification
{
    protected int $codesRemaining = 0;

    public function setCodesRemaining(int $amount): static
    {
        $this->codesRemaining = $amount;

        return $this;
    }

    protected function booted(): void
    {
        $this->greeting = __('notifications/auth/security.recovery-code-was-used.greeting');

        $lines = __('notifications/auth/security.recovery-code-was-used.lines') ?? [];
        $supportEmail = AppConfig::supportEmail();

        foreach ($lines as $line) {
            $this->markdownLine(
                Lang::choice($line, $this->codesRemaining, [
                    'remaining' => $this->codesRemaining,
                    'support' => $supportEmail,
                ]),
            );
        }
    }
}
