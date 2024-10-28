<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use function App\Helpers\defaultEmailSalutation;

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
        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        return (new MailMessage)
            ->subject(__('notifications/auth/reset-password.invalid_user.subject'))
            ->greeting(__('notifications/auth/reset-password.invalid_user.greeting'))
            ->line(__('notifications/auth/reset-password.invalid_user.intro', ['domain' => $domain]))
            ->line(__('notifications/auth/reset-password.invalid_user.line2', ['email' => $notifiable->routeNotificationFor('mail')]))
            ->line(__('notifications/auth/reset-password.invalid_user.login_instructions'))
            ->action(__('notifications/auth/reset-password.invalid_user.login_button'), $this->loginUrl)
            ->line(
                str(__('notifications/auth/reset-password.invalid_user.help_info', ['support' => config('randallwilk.support_email')]))
                    ->inlineMarkdown()
                    ->toHtmlString()
            )
            ->salutation(defaultEmailSalutation());
    }
}
