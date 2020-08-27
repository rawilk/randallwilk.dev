<?php

namespace App\Services\Npm;

use Illuminate\Support\Facades\Http;

class NpmApi
{
    /** @var string */
    protected const BASE_URL = 'https://api.npmjs.org/downloads/range/1000-01-01:3000:01-01/';

    public function getTotalDownloadsForPackage(string $package): int
    {
        $url = static::BASE_URL . $package;

        $response = Http::get($url);

        if (! $response->ok()) {
            return 0;
        }

        return collect($response->json()['downloads'] ?? [])->sum('downloads');
    }
}
