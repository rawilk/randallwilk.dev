<?php

declare(strict_types=1);

namespace App\Notifications\Repositories;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ManualSyncFinished extends Notification
{
    use Queueable;

    public function __construct(private readonly string $batchId, private readonly string $batchName)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Manual Repository Sync Finished')
            ->greeting("Hello {$notifiable->name->first},")
            ->line("A repository sync you triggered with batch id **{$this->batchId}** has now finished running.")
            ->line("The name of the batch is: {$this->batchName}");
    }
}
