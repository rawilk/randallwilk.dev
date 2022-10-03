<?php

namespace App\Listeners\Users;

use App\Events\Users\UserPasswordWasUpdatedEvent;
use App\Notifications\Users\UserPasswordUpdatedNotification;

final class NotifyUserPasswordUpdatedListener
{
    public function handle(UserPasswordWasUpdatedEvent $event): void
    {
        $event->user->notify(new UserPasswordUpdatedNotification);
    }
}
