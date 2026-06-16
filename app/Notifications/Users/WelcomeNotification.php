<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use App\Enums\Queue;
use App\Mail\CustomMailMessage;
use App\Support\AppConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        $domain = AppConfig::appDomain();

        $message = (new CustomMailMessage)
            ->subject(__('notifications/users.welcome.subject', ['domain' => $domain]))
            ->greeting(__('notifications/users.welcome.greeting', ['name' => $notifiable->name->first]))
            ->forEmail($notifiable->email)
            ->replyTo(AppConfig::defaultReplyToEmail())
            ->addTextHeader('X-Context', 'new-user-account')
            ->canReplyTo();

        $lines = __('notifications/users.welcome.lines') ?? [];

        $replacements = [
            'url' => url('/'),
            'email' => e($notifiable->email),
        ];

        foreach ($lines as $line) {
            $message->markdownLine(__($line, $replacements));
        }

        $message->action(__('notifications/users.welcome.action'), filament()->getPanel($this->panelId)->getRequestPasswordResetUrl());

        return $message;
    }
}
