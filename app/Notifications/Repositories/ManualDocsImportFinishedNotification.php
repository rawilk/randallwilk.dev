<?php

declare(strict_types=1);

namespace App\Notifications\Repositories;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use function App\Helpers\defaultEmailSalutation;

class ManualDocsImportFinishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly string $batchId,
        protected readonly string $batchName,
        protected readonly ?Repository $repository = null,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = 'Manual Docs Import Finished';

        if ($this->repository) {
            $subject .= " [{$this->repository->name}]";
        }

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting(false)
            ->salutation(defaultEmailSalutation())
            ->line("A repository docs sync you triggered with batch id **{$this->batchId}** has now finished running.")
            ->line("The name of the batch is: {$this->batchName}");

        if ($this->repository) {
            $mail->line("The repository this job ran for is: **{$this->repository->full_name}**");
        }

        return $mail;
    }
}
