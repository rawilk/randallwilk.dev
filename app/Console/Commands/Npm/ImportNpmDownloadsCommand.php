<?php

declare(strict_types=1);

namespace App\Console\Commands\Npm;

use App\Jobs\Repositories\ImportNpmDownloadsJob;
use Illuminate\Console\Command;

class ImportNpmDownloadsCommand extends Command
{
    protected $signature = 'import:npm-downloads {--repo= : Only import downloads for a specific package}';

    protected $description = 'Import download counts of npm packages.';

    public function handle(): void
    {
        $this->info('Importing downloads from NPM...');

        ImportNpmDownloadsJob::dispatch($this->option('repo'));

        $this->info('NPM download counts were queued to sync!');
    }
}
