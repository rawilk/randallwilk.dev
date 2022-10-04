<?php

declare(strict_types=1);

namespace App\Listeners\TwoFactorAuth;

use Rawilk\LaravelBase\Events\Auth\TwoFactorAuthenticationAppUsed;

final class TwoFactorAuthenticationAppUsedListener
{
    public function handle(TwoFactorAuthenticationAppUsed $event): void
    {
        $event->authenticatorApp->forceFill([
            'last_used_at' => now(),
        ])->save();
    }
}
