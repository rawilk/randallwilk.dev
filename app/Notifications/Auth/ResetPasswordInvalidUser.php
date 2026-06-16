<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Mail\CustomMailMessage;
use App\Support\AppConfig;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordInvalidUser extends Notification
{
    public function __construct(protected readonly string $loginUrl)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(AnonymousNotifiable $notifiable): MailMessage
    {
        $message = (new CustomMailMessage)
            ->subject(__('notifications/auth/reset-password.invalid-user.subject'))
            ->greeting(__('notifications/auth/reset-password.invalid-user.greeting'));

        $introLines = __('notifications/auth/reset-password.invalid-user.intro-lines') ?? [];

        $replacements = [
            'domain' => AppConfig::appDomain(),
            'email' => e($notifiable->routeNotificationFor('mail')),
            'support' => AppConfig::supportEmail(),
        ];

        foreach ($introLines as $line) {
            $message->markdownLine(__($line, $replacements));
        }

        $message->action(__('notifications/auth/reset-password.invalid-user.action'), $this->loginUrl);

        $outroLines = __('notifications/auth/reset-password.invalid-user.outro-lines') ?? [];

        foreach ($outroLines as $line) {
            $message->markdownLine(__($line, $replacements));
        }

        return $message;
    }
}
