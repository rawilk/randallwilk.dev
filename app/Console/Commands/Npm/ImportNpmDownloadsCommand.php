<?php

namespace App\Console\Commands\Npm;

use App\Models\Repository;
use App\Services\Npm\NpmApi;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ImportNpmDownloadsCommand extends Command
{
    protected $signature = 'import:npm-downloads {--repo= : Only import downloads for a specific package}';

    protected $description = 'Import download counts of npm packages';

    public function handle(NpmApi $api): void
    {
        $this->info('Importing downloads from NPM...');

        $this->getNpmPackages()
            ->each(function (Repository $repository) use ($api) {
                $this->comment("Importing downloads for `{$repository->name}`...");

                $repository->downloads = $api->getTotalDownloadsForPackage($repository->name);

                $repository->save();
            });

        $this->info('NPM downloads were imported.');
    }

    protected function getNpmPackages(): Collection
    {
        return Repository::query()
            ->where('language', 'JavaScript')
            ->when($this->option('repo'), fn ($query, $name) => $query->where('name', $name))
            ->get();
    }
}
