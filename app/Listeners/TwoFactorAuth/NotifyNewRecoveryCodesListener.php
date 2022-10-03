<?php

declare(strict_types=1);

namespace App\Listeners\TwoFactorAuth;

use App\Notifications\Users\NewRecoveryCodesNotification;
use Rawilk\LaravelBase\Events\Auth\RecoveryCodesGenerated;

final class NotifyNewRecoveryCodesListener
{
    public function handle(RecoveryCodesGenerated $event): void
    {
        $event->user->notify(new NewRecoveryCodesNotification);
    }
}
