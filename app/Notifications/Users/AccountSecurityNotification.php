<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use App\Enums\QueuesEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

abstract class AccountSecurityNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue(QueuesEnum::MAIL->value);
    }

    abstract protected function greeting(): string;

    abstract protected function line1(): string;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Security alert'))
            ->salutation(false)
            ->greeting($this->greeting())
            ->line($notifiable->email)
            ->line($this->line1())
            ->action(__('Check account'), route($this->actionRoute()))
            ->line(__('You received this email to let you know about important changes to your randallwilk.dev account and services.'));
    }

    protected function actionRoute(): string
    {
        return 'profile.authentication';
    }
}
