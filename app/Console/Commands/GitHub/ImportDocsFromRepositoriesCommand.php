<?php

namespace App\Console\Commands\GitHub;

use App\Jobs\Docs\CleanupDocsImportJob;
use App\Jobs\Docs\CleanupRepositoryFoldersJob;
use App\Jobs\Repositories\ImportDocsFromRepositoryJob;
use App\Support\ValueStores\UpdatedRepositoriesValueStore;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

final class ImportDocsFromRepositoriesCommand extends Command
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
        ];

        Bus::batch([$jobs])
            ->finally(function () {
                CleanupDocsImportJob::dispatch();

                UpdatedRepositoriesValueStore::make()->flush();
            })
            ->dispatch();

        $this->info('Repository docs queued to sync!');
    }

    private function convertRepositoriesToJobs(array $updatedRepositoryNames): array
    {
        $repositoriesWithDocs = $this->getRepositoriesWithDocs();

        return collect($updatedRepositoryNames)
            ->map(fn (string $repositoryName) => $repositoriesWithDocs[$repositoryName] ?? null)
            ->filter()
            ->map(fn (array $repository) => new ImportDocsFromRepositoryJob($repository))
            ->toArray();
    }

    private function getRepositoriesWithDocs(): Collection
    {
        return collect(config('docs.repositories'))->keyBy('repository');
    }

    private function getUpdatedRepositoryNames(): array
    {
        if ($this->option('all')) {
            return collect(config('docs.repositories'))
                ->map(fn (array $repo) => $repo['repository'])
                ->toArray();
        }

        $names = UpdatedRepositoriesValueStore::make()->getNames();

        if ($repo = $this->option('repo')) {
            $names[] = $repo;
        }

        return $names;
    }
}
