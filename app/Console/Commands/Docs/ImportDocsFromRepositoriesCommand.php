<?php

namespace App\Console\Commands\Docs;

use App\Actions\Docs\ImportDocs;
use App\Support\ValueStores\UpdatedRepositoriesValueStore;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class ImportDocsFromRepositoriesCommand extends Command
{
    protected $signature = 'docs:import {--force : Force a refresh of the docs} {--repo= : Only sync docs for a specific repository}';

    protected $description = 'Fetches all docs from all repositories in config/docs.php';

    public function handle(): void
    {
        $this->info('Importing docs...');

        $this->init();

        $updatedRepositoriesValueStore = UpdatedRepositoriesValueStore::make();

        $updatedRepositoryNames = $updatedRepositoriesValueStore->getNames();

        (new ImportDocs($updatedRepositoryNames, $this))->execute();

        $updatedRepositoriesValueStore->flush();

        $this->info('Docs have been imported!');
    }

    private function init(): void
    {
        if (! $this->option('force')) {
            return;
        }

        $repositoryNames = $this->getRepositoriesWithDocs()->keys();

        if ($this->option('repo')) {
            $repositoryNames = $repositoryNames->filter(fn (string $name) => str_ends_with($name, $this->option('repo')))->values();
        }

        $repositoryNames->each(fn (string $name) => UpdatedRepositoriesValueStore::make()->store($name));
    }

    private function getRepositoriesWithDocs(): Collection
    {
        return collect(config('docs.repositories'))->keyBy('repository');
    }
}
