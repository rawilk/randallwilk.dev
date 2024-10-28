<?php

declare(strict_types=1);

namespace App\Services\Npm;

use Illuminate\Support\Facades\Http;

use function collect;

class NpmApi
{
    protected const string BASE_URL = 'https://api.npmjs.org/downloads/range/[start]:[end]/[package]';

    // Chose this date because I started professionally developing in that year.
    protected const string START = '2015-01-01';

    public function getTotalDownloadsForPackage(string $package): int
    {
        return rescue(function () use ($package) {
            $response = Http::get($this->getApiUrl($package));

            if (! $response->ok()) {
                return 0;
            }

            return collect($response->json()['downloads'] ?? [])->sum('downloads');
        }, 0);
    }

    protected function getApiUrl(string $package): string
    {
        $replacements = [
            '/\[start\]/' => static::START,
            '/\[end\]/' => now()->toDateString(),
            '/\[package\]/' => $package,
        ];

        return preg_replace(
            array_keys($replacements),
            array_values($replacements),
            static::BASE_URL,
        );
    }
}
