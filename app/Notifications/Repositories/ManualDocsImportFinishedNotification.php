<?php

declare(strict_types=1);

namespace App\Notifications\Repositories;

use App\Mail\CustomMailMessage;
use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Stringable;

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
        $subject = str('Manual Docs Import Finished')
            ->when(
                filled($this->repository),
                fn (Stringable $str) => $str->append(" [{$this->repository->name}]")
            )
            ->value();

        return (new CustomMailMessage)
            ->subject($subject)
            ->forEmail($notifiable->email)
            ->line("A repository docs sync you triggered with batch id **{$this->batchId}** has now finished running.")
            ->line("The name of the batch is: {$this->batchName}")
            ->when(
                filled($this->repository),
                fn (CustomMailMessage $message) => $message->line(
                    "The repository this job ran for is: **{$this->repository->full_name}**"
                )
            );
    }
}
