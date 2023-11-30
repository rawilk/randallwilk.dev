<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use App\Enums\QueuesEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $password)
    {
        $this->onQueue(QueuesEnum::MAIL->value);
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject(__('New user account for randallwilk.dev'))
            ->greeting(__('Hello :name!', ['name' => $notifiable->name->first]))
            ->salutation(false)
            ->line(__('A new user account has been created for you at [:url](:url).', ['url' => url('/')]))
            ->line(__('You may login with your email address and the password we have created for you: **:password**', ['password' => $this->password]))
            ->action(__('Login'), route('login'))
            ->line(__('Please note: We strongly recommend changing your password after login for the first time.'));

        if ($notifiable->github_id) {
            $message->line(__('You may also login using your linked GitHub account as well: :username', ['username' => $notifiable->github_username]));
        }

        return $message;
    }
}
