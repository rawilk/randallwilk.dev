<?php

namespace App\Jobs;

use App\Actions\Docs\ImportDocs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportDocsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private array $repositoryNames)
    {
    }

    public function handle(): void
    {
        (new ImportDocs($this->repositoryNames))->execute();
    }
}
