<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class AlpineRippleDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'index.html',
            'package.json',
        ];
    }

    public static function snippetLanguage(string $version): string
    {
        return 'html';
    }

    public static function snippet(string $version): string
    {
        return <<<'HTML'
        <button type="button"
                x-data
                x-ripple
        >
            Click m
        </button>
        HTML;
    }
}
