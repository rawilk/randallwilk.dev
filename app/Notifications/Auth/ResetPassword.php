<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Mail\CustomMailMessage;
use App\Support\AppConfig;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

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

        $message = (new CustomMailMessage)
            ->forEmail($notifiable->email)
            ->subject(__('notifications/auth/reset-password.subject', ['domain' => $domain]))
            ->greeting(__('notifications/auth/reset-password.greeting'))
            ->addTextHeader('X-Context', 'password-reset');

        $introLines = __('notifications/auth/reset-password.intro-lines') ?? [];

        $replacements = [
            'domain' => $domain,
            'email' => e($notifiable->email),
            'support' => AppConfig::supportEmail(),
            'request_url' => $this->requestUrl,
            'expiration' => $this->getLinkExpiration(),
        ];

        foreach ($introLines as $line) {
            $message->markdownLine(__($line, $replacements));
        }

        $message->action(__('notifications/auth/reset-password.action'), $this->resetUrl($notifiable));

        $outroLines = __('notifications/auth/reset-password.outro-lines') ?? [];

        foreach ($outroLines as $line) {
            $message->markdownLine(__($line, $replacements));
        }

        return $message;
    }

    protected function resetUrl($notifiable): string
    {
        return $this->url;
    }

    protected function getDomain(): string
    {
        return AppConfig::appDomain();
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
        return AppConfig::passwordResetDecayMinutes();
    }
}
