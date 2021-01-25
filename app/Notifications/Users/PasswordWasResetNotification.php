<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

final class PasswordWasResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[' . config('app.name') . '] Password Was Reset')
            ->greeting("Hello {$notifiable->name->first},")
            ->line('Your password has been reset on ' . Config::get('app.url') . '. If you did not make this change, please contact me at ' . Config::get('site.contact.email'));
    }
}
