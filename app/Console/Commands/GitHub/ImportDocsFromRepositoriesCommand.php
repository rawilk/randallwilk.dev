<?php

declare(strict_types=1);

namespace App\Console\Commands\GitHub;

use App\Jobs\Docs\CleanupDocsImportJob;
use App\Jobs\Docs\CleanupRepositoryFoldersJob;
use App\Jobs\Docs\RefreshDocsCacheJob;
use App\Jobs\Repositories\ImportDocsFromRepositoryJob;
use App\Support\ValueStores\UpdatedRepositoriesValueStore;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class ImportDocsFromRepositoriesCommand extends Command
{
    protected $signature = 'import:docs {--repo= : Import docs from a specific repo} {--all}';

    protected $description = 'Fetches docs from all repositories.';

    public function handle(): void
    {
        $this->info('Importing docs...');

        $updatedRepositoryNames = $this->getUpdatedRepositoryNames();

        $this->info('Updating ' . count($updatedRepositoryNames) . ' repositories.');

        $jobs = [
            new CleanupRepositoryFoldersJob,
            ...$this->convertRepositoriesToJobs($updatedRepositoryNames),
            new CleanupDocsImportJob,
            new RefreshDocsCacheJob,
        ];

        Bus::batch([$jobs])
            ->finally(function () {
                UpdatedRepositoriesValueStore::make()->flush();
            })
            ->dispatch();

        $this->info('Repository docs queued to sync!');
    }

    protected function convertRepositoriesToJobs(array $updatedRepositoryNames): array
    {
        $repositoriesWithDocs = $this->getRepositoriesWithDocs();

        return collect($updatedRepositoryNames)
            ->map(fn (string $repositoryName) => $repositoriesWithDocs[$repositoryName] ?? null)
            ->filter()
            ->map(fn (array $repository) => new ImportDocsFromRepositoryJob($repository))
            ->all();
    }

    protected function getRepositoriesWithDocs(): Collection
    {
        return collect(config('docs.repositories'))->keyBy('repository');
    }

    protected function getUpdatedRepositoryNames(): array
    {
        if ($this->option('all')) {
            return collect(config('docs.repositories'))
                ->map(fn (array $repo) => $repo['repository'])
                ->all();
        }

        $names = UpdatedRepositoriesValueStore::make()->getNames();

        if ($repo = $this->option('repo')) {
            $names[] = $repo;
        }

        return $names;
    }
}
