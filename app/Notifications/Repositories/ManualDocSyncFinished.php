<?php

namespace App\Notifications\Repositories;

use App\Models\GitHub\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ManualDocSyncFinished extends Notification
{
    use Queueable;

    public function __construct(private readonly string $batchId, private readonly string $batchName, private readonly ?Repository $repository = null)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = 'Manual Docs Sync Finished';

        if ($this->repository) {
            $subject .= " [{$this->repository->name}]";
        }

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name->first},")
            ->line("A repository docs sync you triggered with batch id **{$this->batchId}** has now finished running.")
            ->line("The name of the batch is: {$this->batchName}");

        if ($this->repository) {
            $mail->line("The repository this job ran for is: **{$this->repository->full_name}**");
        }

        return $mail;
    }
}
