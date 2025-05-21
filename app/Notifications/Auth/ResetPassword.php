<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Mail\CustomMailMessage;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Uri;

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
        $domain = $this->getDomain();

        return (new CustomMailMessage)
            ->forEmail($notifiable->email)
            ->subject(__('notifications/auth/reset-password.subject', ['domain' => $domain]))
            ->greeting(__('notifications/auth/reset-password.greeting'))
            ->line(__('notifications/auth/reset-password.intro', ['domain' => $domain, 'email' => $notifiable->email]))
            ->line(__('notifications/auth/reset-password.link_instructions'))
            ->action(__('notifications/auth/reset-password.button'), $this->resetUrl($notifiable))
            ->markdownLine(
                __('notifications/auth/reset-password.line3', ['support' => config('randallwilk.support_email')])
            )
            ->markdownLine(
                __('notifications/auth/reset-password.expire_info', [
                    'expiration' => $this->getLinkExpiration(),
                    'request_url' => $this->requestUrl,
                ])
            )
            ->line(__('notifications/auth/reset-password.multiple_requests_notice'))
            ->addTextHeader('X-Context', 'password-reset');
    }

    protected function resetUrl($notifiable): string
    {
        return $this->url;
    }

    protected function getDomain(): string
    {
        return Uri::of(config('app.url'))
            ->host();
    }

    protected function getLinkExpiration(): string
    {
        return now()->diffForHumans(
            other: now()->addMinutes($this->getDecayMinutes()),
            syntax: true,
            parts: 2,
        );
    }

    protected function getDecayMinutes(): int
    {
        return config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
    }
}
