<?php

declare(strict_types=1);

namespace App\Jobs\Repositories;

use App\Enums\ProgrammingLanguage;
use App\Models\Repository;
use App\Services\Npm\NpmApi;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ImportNpmDownloadsJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(protected readonly ?string $package = null)
    {
    }

    public function handle(): void
    {
        if ($this->batch()?->canceled()) {
            return;
        }

        $api = app(NpmApi::class);

        $this->getNpmPackages()
            ->each(function (Repository $repository) use ($api) {
                $repository->update([
                    'downloads' => $api->getTotalDownloadsForPackage($repository->nameForNpm()),
                ]);
            });
    }

    protected function getNpmPackages(): Collection
    {
        return Repository::query()
            ->where('language', ProgrammingLanguage::JavaScript)
            ->when($this->package, fn ($query, $name) => $query->where('name', $name)->orWhere('scoped_name', $name))
            ->get();
    }
}
