<?php

namespace App\Console\Commands\Docs;

use App\Support\ValueStores\UpdatedRepositoriesValueStore;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use React\ChildProcess\Process;
use React\EventLoop\Factory;
use React\EventLoop\StreamSelectLoop;
use function React\Promise\all;
use Spatie\Sheets\Sheets;
use function WyriHaximus\React\childProcessPromise;

final class ImportDocsFromRepositoriesCommand extends Command
{
    protected $signature = 'docs:import';

    protected $description = 'Fetches all docs from all repositories in config/docs.php';

    public function handle(): void
    {
        $this->info('Importing docs...');

        $loop = Factory::create();

        $updatedRepositoriesValueStore = UpdatedRepositoriesValueStore::make();

        $updatedRepositoryNames = $updatedRepositoriesValueStore->getNames();

        $this
            ->convertRepositoriesToProcesses($updatedRepositoryNames, $loop)
            ->pipe(fn (Collection $processes) => $this->wrapInPromise($processes));

        $loop->run();

        $updatedRepositoriesValueStore->flush();

        $this->info('Docs have been imported!');
    }

    private function convertRepositoriesToProcesses(
        array $updatedRepositoryNames,
        StreamSelectLoop $loop
    ): Collection {
        $repositoriesWithDocs = $this->getRepositoriesWithDocs();

        return collect($updatedRepositoryNames)
            ->map(fn (string $repositoryName) => $repositoriesWithDocs[$repositoryName] ?? null)
            ->filter()
            ->flatMap(function (array $repository) {
                return collect($repository['branches'])
                    ->map(fn (string $alias, string $branch) => [$repository, $alias, $branch])
                    ->toArray();
            })
            ->mapSpread(function (array $repository, string $alias, string $branch) use ($loop) {
                $process = $this->createProcessComponent($repository, $branch, $alias);

                $this->info("Created import process for {$repository['name']} {$branch}");

                return childProcessPromise($loop, $process);
            });
    }

    private function getRepositoriesWithDocs(): Collection
    {
        return collect(config('docs.repositories'))->keyBy('repository');
    }

    private function createProcessComponent(array $repository, string $branch, string $alias): Process
    {
        $accessToken = Config::get('services.github.docs_access_token');
        $publicDocsAssetPath = public_path('doc_files');

        return new Process(
            <<<BASH
                rm -rf storage/docs/{$repository['name']}/{$alias} \
                && mkdir -p storage/docs/{$repository['name']}/{$alias} \
                && mkdir -p storage/docs-temp/{$repository['name']}/{$alias} \
                && cd storage/docs-temp/{$repository['name']}/{$alias} \
                && git init \
                && git config core.sparseCheckout true \
                && echo "/docs" >> .git/info/sparse-checkout \
                && git remote add -f origin https://{$accessToken}@github.com/rawilk/{$repository['name']}.git \
                && git pull origin ${branch} \
                && cp -r docs/* ../../../docs/{$repository['name']}/{$alias} \
                && echo "---\ntitle: {$repository['name']}\ncategory: {$repository['category']}\n---" > ../../../docs/{$repository['name']}/_index.md \
                && cd docs/ \
                && rm -rf {$publicDocsAssetPath}/{$repository['name']}/{$alias} \
                && find . -not -name '*.md' | cpio -pdm {$publicDocsAssetPath}/{$repository['name']}/{$alias}/
            BASH
        );
    }

    private function wrapInPromise(Collection $processes): void
    {
        all($processes->toArray())
            ->then(function (): void {
                $this->info('Fetched docs from all repositories.');

                $this->warn('Caching sheets...');

                $pages = app(Sheets::class)->collection('docs')->all()->sortBy('sort');

                Cache::store('docs')->forever('docs', $pages);

                $this->info('Done caching sheets.');
            })
            ->always(function (): void {
                File::deleteDirectory(storage_path('docs-temp/'));
            });
    }

    public function handle2(): void
    {
        $loop = Factory::create();

        $repositories = $this->getRepositories();

        $accessToken = config('services.github.docs_access_token');

        $processes = [];

        $publicDocsAssetPath = public_path('doc_files');

        foreach ($repositories as $repository) {
            foreach ($repository['branches'] as $branch => $alias) {
                $process = new Process(
                    <<<BASH
                    mkdir -p storage/docs/{$repository['name']}/{$alias} \
                    && mkdir -p storage/docs-temp/{$repository['name']}/{$alias} \
                    && cd storage/docs-temp/{$repository['name']}/{$alias} \
                    && git init \
                    && git config core.sparseCheckout true \
                    && echo "/docs" >> .git/info/sparse-checkout \
                    && git remote add -f origin https://{$accessToken}@github.com/rawilk/{$repository['name']}.git \
                    && git pull origin ${branch} \
                    && cp -r docs/* ../../../docs/{$repository['name']}/{$alias} \
                    && echo "---\ntitle: {$repository['name']}\ncategory: {$repository['category']}\n---" > ../../../docs/{$repository['name']}/_index.md \
                    && cd docs/ \
                    && rm -rf {$publicDocsAssetPath}/{$repository['name']}/{$alias} \
                    && find . -not -name '*.md' | cpio -pdm {$publicDocsAssetPath}/{$repository['name']}/{$alias}/
                BASH
                );

                $processes[] = childProcessPromise($loop, $process);
            }
        }

        all($processes)
            ->then(function ($output) {
                print_r($output);

                $this->info('Fetched docs from all repositories.');

                $this->info('Caching sheets.');

                $pages = app(Sheets::class)->collection('docs')->all()->sortBy('sort');

                cache()->store('docs')->forever('docs', $pages);

                $this->info('Done caching Sheets.');
            })
            ->always(fn () => File::deleteDirectory(storage_path('docs-temp/')));

        $loop->run();
    }

    private function getRepositories(): array
    {
        return config('docs.repositories');
    }
}
