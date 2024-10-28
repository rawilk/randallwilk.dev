<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use App\Enums\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use function App\Helpers\defaultEmailSalutation;

class WelcomeNotification extends Notification implements ShouldDispatchAfterCommit, ShouldQueue
{
    use Queueable;

    public function __construct(protected string $panelId)
    {
        $this->onQueue(Queue::Mail->value);
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        return (new MailMessage)
            ->subject(__('notifications/users.welcome.subject', ['domain' => $domain]))
            ->greeting(__('notifications/users.welcome.greeting', ['name' => $notifiable->name->first]))
            ->salutation(defaultEmailSalutation())
            ->line(__('notifications/users.welcome.line1', ['url' => url('/')]))
            ->line(__('notifications/users.welcome.line2', ['email' => $notifiable->email]))
            ->action(__('notifications/users.welcome.action'), filament()->getPanel($this->panelId)->getRequestPasswordResetUrl());
    }
}
