<?php

declare(strict_types=1);

namespace App\Jobs\Repositories;

use App\Models\Repository;
use GuzzleHttp\Client;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

final class ImportPackagistDownloadsJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $username, private readonly ?string $packageName = null)
    {
    }

    public function handle(): void
    {
        if ($this->batch()?->canceled()) {
            return;
        }

        $this->getPackages()
            ->each(function (array $package) {
                $name = explode('/', $package['name'])[1];

                $downloadCount = $package['downloads']['total'];

                Repository::withTrashed()->where('name', $name)->update(['downloads' => $downloadCount]);
            });
    }

    private function getPackages(): Collection
    {
        $packagist = new PackagistClient(new Client, new PackagistUrlGenerator);

        if ($this->packageName) {
            return rescue(function () use ($packagist) {
                return collect([
                    $packagist->getPackage("{$this->username}/{$this->packageName}")['package'] ?? null,
                ])->filter();
            }, collect());
        }

        return collect($packagist->getPackagesNamesByVendor($this->username)['packageNames'])
            ->map(fn ($packageName) => $packagist->getPackage($packageName)['package']);
    }
}
