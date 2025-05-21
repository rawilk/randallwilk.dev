<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Notifications\Messages\MailMessage;
use Symfony\Component\Mime\Message;

class CustomMailMessage extends MailMessage
{
    protected ?string $intendedEmail = null;

    protected bool $canReplyTo = false;

    public function canReplyTo(bool $condition = true): static
    {
        $this->canReplyTo = $condition;

        return $this;
    }

    public function forEmail(?string $email): static
    {
        $this->intendedEmail = $email;

        return $this;
    }

    public function addTextHeader(string $name, string $value): static
    {
        return $this->withSymfonyMessage(function (Message $message) use ($name, $value): void {
            $message->getHeaders()->addTextHeader($name, $value);
        });
    }

    public function markdownLine(string|Htmlable $content): static
    {
        return $this->line(
            str($content)->inlineMarkdown()->toHtmlString()
        );
    }

    public function data(): array
    {
        return [
            ...parent::data(),
            'intendedEmail' => $this->intendedEmail,
            'canReplyTo' => $this->canReplyTo,
        ];
    }
}
