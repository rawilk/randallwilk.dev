<?php

declare(strict_types=1);

namespace App\Jobs\Docs;

use App\Docs\Docs;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefreshDocsCacheJob implements ShouldQueue
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

        cache()->store('docs')->clear();

        foreach ($this->getRepositories() as $slug) {
            Log::channel('docs')->info("Refreshing docs cache for: {$slug}");

            app(Docs::class)->getRepository($slug);
        }
    }

    protected function getRepositories(): array
    {
        return collect(config('docs.repositories'))
            ->map(fn (array $repo) => $repo['name'])
            ->all();
    }
}
