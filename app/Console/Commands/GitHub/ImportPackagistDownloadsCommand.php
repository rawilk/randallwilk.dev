<?php

declare(strict_types=1);

namespace App\Console\Commands\GitHub;

use App\Jobs\Repositories\ImportPackagistDownloadsJob;
use Illuminate\Console\Command;

class ImportPackagistDownloadsCommand extends Command
{
    protected $signature = 'import:packagist-downloads {--repo= : Only import downloads for a specific package}';

    protected $description = 'Import download counts of my packages.';

    public function handle(): void
    {
        $this->info('Starting download count sync...');

        ImportPackagistDownloadsJob::dispatch(
            config('services.github.username'),
            $this->option('repo'),
        );

        $this->info('Download counts were queued to sync!');
    }
}
