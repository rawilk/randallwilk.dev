<?php

declare(strict_types=1);

namespace App\Services\Npm;

use Illuminate\Support\Facades\Http;

final class NpmApi
{
    private const BASE_URL = 'https://api.npmjs.org/downloads/range/1000-01-01:3000:01-01/';

    public function getTotalDownloadsForPackage(string $package): int
    {
        return rescue(function () use ($package) {
            $response = Http::get(self::BASE_URL . $package);

            if (! $response->ok()) {
                return 0;
            }

            return collect($response->json()['downloads'] ?? [])->sum('downloads');
        }, 0);
    }
}
