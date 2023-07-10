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

final class CleanupDocsImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function handle(): void
    {
        if ($this->batch()?->canceled()) {
            return;
        }

        $path = storage_path('docs-temp');
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }
    }
}
