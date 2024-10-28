<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

use function App\Helpers\defaultEmailSalutation;

class ResetPassword extends BaseResetPassword
{
    protected string $url;

    protected string $requestUrl;

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function setRequestUrl(string $url): static
    {
        $this->requestUrl = $url;

        return $this;
    }

    public function toMail($notifiable): MailMessage
    {
        $decayMinutes = $this->getDecayMinutes();
        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        return (new MailMessage)
            ->subject(__('notifications/auth/reset-password.subject', ['domain' => $domain]))
            ->greeting(__('notifications/auth/reset-password.greeting'))
            ->line(__('notifications/auth/reset-password.intro', ['domain' => $domain, 'email' => $notifiable->email]))
            ->line(__('notifications/auth/reset-password.link_instructions'))
            ->action(__('notifications/auth/reset-password.button'), $this->resetUrl($notifiable))
            ->line(
                str(__('notifications/auth/reset-password.line3', ['support' => config('randallwilk.support_email')]))
                    ->inlineMarkdown()
                    ->toHtmlString()
            )
            ->line(
                str(__('notifications/auth/reset-password.expire_info', ['count' => $decayMinutes, 'request_url' => $this->requestUrl]))
                    ->inlineMarkdown()
                    ->toHtmlString()
            )
            ->line(__('notifications/auth/reset-password.multiple_requests_notice'))
            ->salutation(defaultEmailSalutation());
    }

    protected function resetUrl($notifiable): string
    {
        return $this->url;
    }

    protected function getDecayMinutes(): int
    {
        return config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
    }
}
