<?php

namespace App\Jobs\Repositories;

use App\Exceptions\Docs\DocsImportException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\Fork\Fork;
use Symfony\Component\Process\Process;

final class ImportDocsFromRepositoryJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(private readonly array $repository)
    {
    }

    public function handle(): void
    {
        if ($this->batch()?->canceled()) {
            return;
        }

        $callables = $this->convertRepositoryToCallables();

        Fork::new()
            ->concurrent(4)
            ->run(...$callables);
    }

    private function convertRepositoryToCallables(): array
    {
        return collect([$this->repository])
            ->flatMap(function (array $repository) {
                return collect($repository['branches'])
                    ->map(fn ($alias, string $branch) => [$repository, $alias, $branch])
                    ->values()
                    ->toArray();
            })
            ->mapSpread(function (array $repository, string $alias, string $branch) {
                $process = $this->createProcessComponent($repository, $branch, $alias);

                Log::channel('docs')->info("Created import process for {$repository['name']} {$branch}");

                return function () use ($process, $repository, $branch) {
                    $process->run();

                    if (! $process->isSuccessful()) {
                        Log::channel('docs')->error($process->getErrorOutput());
                        report(new DocsImportException("Import for repository {$repository['name']} was unsuccessful: {$process->getErrorOutput()}"));

                        return;
                    }

                    Log::channel('docs')->info("Import process finished for {$repository['name']} {$branch}");

                    cache()->store('docs')->forget($repository['name']);
                };
            })
            ->toArray();
    }

    private function createProcessComponent(array $repository, string $branch, string $alias): Process
    {
        $accessToken = config('services.github.docs_access_token');
        $publicDocsAssetPath = public_path('doc-files');
        $username = config('services.github.username');

        return Process::fromShellCommandline(
            <<<BASH
            rm -rf storage/docs/{$repository['name']}/{$alias} \
            && mkdir -p storage/docs/{$repository['name']}/{$alias} \
            && mkdir -p storage/docs-temp/{$repository['name']}/{$alias} \
            && cd storage/docs-temp/{$repository['name']}/{$alias} \
            && rm -rf .git \
            && git init \
            && git config core.sparseCheckout true \
            && echo "/docs" >> .git/info/sparse-checkout \
            && git remote add -f origin https://{$accessToken}@github.com/{$username}/{$repository['name']}.git \
            && git pull origin {$branch} \
            && cp -r docs/* ../../../docs/{$repository['name']}/{$alias} \
            && echo "---\ntitle: {$repository['name']}\ncategory: {$repository['category']}\n---" > ../../../docs/{$repository['name']}/_index.md \
            && cd docs/ \
            && find . -not -name '*.md' | cpio -pdm {$publicDocsAssetPath}/{$repository['name']}/{$alias}/
            BASH,
            base_path()
        );
    }
}
