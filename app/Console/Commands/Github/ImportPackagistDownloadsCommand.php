<?php

namespace App\Console\Commands\Github;

use App\Models\Repository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

final class ImportPackagistDownloadsCommand extends Command
{
    private const USERNAME = 'rawilk';

    protected $signature = 'import:packagist-downloads {--repo= : Only import downloads for a specific package}';

    protected $description = 'Import download counts of packages.';

    public function handle(): void
    {
        $this->info('Importing downloads from Packagist...');

        $this->getPackages()
            ->each(function ($package) {
                $name = explode('/', $package['name'])[1];

                $this->comment("Getting downloads for `{$name}`");

                $downloadCount = $package['downloads']['total'];

                Repository::where('name', $name)->update(['downloads' => $downloadCount]);
            });

        $this->info('Downloads all synced!');
    }

    private function getPackages(): Collection
    {
        $packagist = new PackagistClient(client: new Client, url: new PackagistUrlGenerator);

        if ($this->option('repo')) {
            try {
                return collect([$packagist->getPackage(self::USERNAME . '/' . $this->option('repo'))['package'] ?? null])->filter();
            } catch (\Exception) {
                return collect();
            }
        }

        return collect($packagist->getPackagesNamesByVendor(self::USERNAME)['packageNames'])
            ->map(fn ($packageName) => $packagist->getPackage($packageName)['package']);
    }
}
