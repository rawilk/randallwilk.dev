<?php

declare(strict_types=1);

namespace App\Listeners\Webauthn;

use App\Notifications\Users\WebauthnKeyRemovedNotification;
use Rawilk\LaravelBase\Events\Webauthn\WebauthnKeyWasDeleted;

final class NotifyWebauthnKeyDeletedListener
{
    public function handle(WebauthnKeyWasDeleted $event): void
    {
        if (! $event->webauthnKey->user) {
            return;
        }

        $event->webauthnKey->user->notify(new WebauthnKeyRemovedNotification);
    }
}
