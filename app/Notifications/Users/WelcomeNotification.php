<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use App\Enums\Queue;
use App\Mail\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Uri;

class WelcomeNotification extends Notification implements ShouldDispatchAfterCommit, ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly string $panelId)
    {
        $this->onQueue(Queue::Mail);
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $domain = Uri::of(config('app.url'))->host();

        return (new CustomMailMessage)
            ->subject(__('notifications/users.welcome.subject', ['domain' => $domain]))
            ->greeting(__('notifications/users.welcome.greeting', ['name' => $notifiable->name->first]))
            ->forEmail($notifiable->email)
            ->replyTo(config('randallwilk.contact.email'))
            ->line(__('notifications/users.welcome.line1', ['url' => url('/')]))
            ->line(__('notifications/users.welcome.line2', ['email' => $notifiable->email]))
            ->action(__('notifications/users.welcome.action'), filament()->getPanel($this->panelId)->getRequestPasswordResetUrl())
            ->addTextHeader('X-Context', 'new-user-account')
            ->canReplyTo();
    }
}
