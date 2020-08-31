<?php

namespace App\Console\Commands\Docs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use React\ChildProcess\Process;
use React\EventLoop\Factory;
use function React\Promise\all;
use Spatie\Sheets\Sheets;
use function WyriHaximus\React\childProcessPromise;

class ImportDocsFromRepositoriesCommand extends Command
{
    protected $signature = 'docs:import';

    protected $description = 'Fetches all docs from all repositories in config/docs.php';

    public function handle(): void
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
