<?php

declare(strict_types=1);

namespace App\Console\Commands\GitHub;

use App\Jobs\Repositories\ImportRepositoriesJob;
use Illuminate\Console\Command;

class ImportGitHubRepositoriesCommand extends Command
{
    protected $signature = 'import:github-repositories {--repo= : Only import a specific (public) repository}';

    protected $description = 'Import public GitHub repositories.';

    public function handle(): void
    {
        $this->info('Syncing all public repositories...');

        ImportRepositoriesJob::dispatch(
            config('services.github.username'),
            $this->option('repo'),
        );

        $this->info('Repositories were queued to sync.');
    }
}
