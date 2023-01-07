<?php

namespace App\Jobs\Repositories;

use App\Enums\ProgrammingLanguageEnum;
use App\Models\GitHub\Repository;
use App\Services\Npm\NpmApi;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

final class ImportNpmDownloadsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(private readonly ?string $package = null)
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
                $repository->forceFill(['downloads' => $api->getTotalDownloadsForPackage($repository->nameForNpm())])->save();
            });
    }

    private function getNpmPackages(): Collection
    {
        return Repository::query()
            ->where('language', ProgrammingLanguageEnum::JAVASCRIPT->value)
            ->when($this->package, fn ($query, $name) => $query->where('name', $name)->orWhere('scoped_name', $name))
            ->get();
    }
}
