<?php

declare(strict_types=1);

namespace App\Actions\Docs;

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

final class ImportDocs
{
    public function __construct(private array $updatedRepositoryNames, private null | Command $command = null)
    {
    }

    public function execute(): void
    {
        $loop = Factory::create();

        $this
            ->convertRepositoriesToProcesses($loop)
            ->pipe(fn (Collection $processes) => $this->wrapInPromise($processes));

        $loop->run();
    }

    private function convertRepositoriesToProcesses(StreamSelectLoop $loop): Collection
    {
        $repositoriesWithDocs = $this->getRepositoriesWithDocs();

        return collect($this->updatedRepositoryNames)
            ->map(fn (string $repositoryName) => $repositoriesWithDocs[$repositoryName] ?? null)
            ->filter()
            ->flatMap(function (array $repository) {
                return collect($repository['branches'])
                    ->map(fn (string $alias, string $branch) => [$repository, $alias, $branch])
                    ->toArray();
            })
            ->mapSpread(function (array $repository, string $alias, string $branch) use ($loop) {
                $process = $this->createProcessComponent(repository: $repository, branch: $branch, alias: $alias);

                if ($this->command) {
                    $this->command->info("Created import process for {$repository['name']} {$branch}");
                }

                return childProcessPromise($loop, $process);
            });
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
                if ($this->command) {
                    $this->command->info('Fetched docs from all repositories...');

                    $this->command->warn('Caching sheets...');
                }

                $pages = app(Sheets::class)->collection('docs')->all()->sortBy('sort');

                Cache::store('docs')->forever('docs', $pages);

                if ($this->command) {
                    $this->command->info('Done caching sheets...');
                }
            })
            ->always(function (): void {
                File::deleteDirectory(storage_path('docs-temp/'));
            });
    }

    private function getRepositoriesWithDocs(): Collection
    {
        return collect(config('docs.repositories'))->keyBy('repository');
    }
}
