<?php

declare(strict_types=1);

namespace App\Notifications\Repositories;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use function App\Helpers\defaultEmailSalutation;

class ManualRepositorySyncFinishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly string $batchId, protected readonly string $batchName)
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
            ->greeting(false)
            ->line("A repository sync you triggered with batch id **{$this->batchId}** has now finished running.")
            ->line("The name of the batch is: {$this->batchName}")
            ->salutation(defaultEmailSalutation());
    }
}
