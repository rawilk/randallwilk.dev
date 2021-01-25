<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $password)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('New user account for randallwilk.dev')
            ->greeting("Hello {$notifiable->name->first}!")
            ->salutation(false)
            ->line('A user account has been created for you at [' . url('/') . '](' . url('/') . ').')
            ->line("You may login with your email address and the password we have created for you: **{$this->password}**")
            ->action(__('Login'), route('login'))
            ->line(__('Please note: We strongly recommend changing your password after you login.'));

        if ($notifiable->github_id) {
            $message->line('You may also login using your linked GitHub account as well: ' . $notifiable->github_username);
        }

        return $message;
    }
}
