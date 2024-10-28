<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

class LaravelAppKeyRotatorDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'deploy.sh',
            'composer.json',
        ];
    }

    public static function snippetLanguage(string $version): string
    {
        return 'shell';
    }

    public static function snippet(string $version): string
    {
        return <<<'BASH'
        #!/bin/bash

        php artisan app-key-rotator:rotate
        BASH;
    }
}
