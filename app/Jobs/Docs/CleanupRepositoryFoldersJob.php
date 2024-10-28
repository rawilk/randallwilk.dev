<?php

declare(strict_types=1);

namespace App\Jobs\Docs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

class CleanupRepositoryFoldersJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        if ($this->batch()?->canceled()) {
            return;
        }

        $publicDocsPath = public_path('doc-files');
        $storageDocsPath = storage_path('docs');

        File::ensureDirectoryExists($publicDocsPath);
        File::ensureDirectoryExists($storageDocsPath);

        $directoriesToKeep = collect(config('docs.repositories'))->pluck('name');

        $finder = new Finder;
        $directories = $finder->in([$storageDocsPath, $publicDocsPath])->depth(0)->directories();

        foreach ($directories as $directory) {
            if (! $directoriesToKeep->contains($directory->getFilename())) {
                File::deleteDirectory($directory->getRealPath());
            }
        }
    }
}
