<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use const PHP_URL_HOST;

use App\Enums\Queue;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use function App\Helpers\defaultEmailSalutation;

abstract class AccountSecurityNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $greeting = '';

    protected array $lines = [];

    protected ?string $url = null;

    protected ?string $ip = null;

    protected ?CarbonInterface $date = null;

    protected ?string $browser = null;

    protected ?string $platform = null;

    protected ?string $location = null;

    public function __construct()
    {
        $this->onQueue(Queue::Mail->value);
    }

    abstract protected function booted(): void;

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function fromIp(string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function onDate(CarbonInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function usingBrowser(bool|string $browser): static
    {
        if (is_bool($browser)) {
            return $this;
        }

        $this->browser = $browser;

        return $this;
    }

    public function onPlatform(bool|string $platform): static
    {
        if (is_bool($platform)) {
            return $this;
        }

        $this->platform = $platform;

        return $this;
    }

    public function fromLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $this->booted();

        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        $message = (new MailMessage)
            ->subject($this->subject())
            ->salutation(defaultEmailSalutation())
            ->greeting($this->greeting)
            ->line($notifiable->email);

        foreach ($this->lines as $line) {
            $message->line($line);
        }

        if ($url = $this->getUrl()) {
            $message->action($this->checkAccountbuttonText(), $url);
        }

        $message->line(__('notifications/auth/security.defaults.reason_for_email', ['domain' => $domain]));

        $message->line($this->buildRequestDetails());

        return $message;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    protected function line(string|Htmlable $line): static
    {
        $this->lines[] = $line;

        return $this;
    }

    protected function markdownLine(string $line): static
    {
        $this->lines[] = str($line)->inlineMarkdown()->toHtmlString();

        return $this;
    }

    protected function subject(): string
    {
        return __('notifications/auth/security.defaults.subject');
    }

    protected function checkAccountButtonText(): string
    {
        return __('notifications/auth/security.defaults.check_account_button');
    }

    protected function buildRequestDetails(): Htmlable
    {
        $details = [__('notifications/auth/security.request_details.label')];

        if ($this->location) {
            $details[] = __('notifications/auth/security.request_details.location', ['location' => $this->location]);
        }

        if ($this->ip) {
            $details[] = __('notifications/auth/security.request_details.ip', ['ip' => $this->ip]);
        }

        if ($this->date) {
            // Date format example: "Tue, 20 Jul 2021 12:00 PM (EDT -0400)"
            $details[] = __('notifications/auth/security.request_details.date', ['date' => $this->date->format('D, j M Y g:i A (T O)')]);
        }

        if ($this->browser) {
            $details[] = __('notifications/auth/security.request_details.browser', ['browser' => $this->browser]);
        }

        if ($this->platform) {
            $details[] = __('notifications/auth/security.request_details.platform', ['platform' => $this->platform]);
        }

        return str(implode('<br>', $details))->markdown()->toHtmlString();
    }
}
