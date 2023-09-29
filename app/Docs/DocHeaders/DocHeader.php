<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

interface DocHeader
{
    /**
     * @return array<int, string>
     */
    public static function heroTabs(string $version): array;

    public static function snippetLanguage(string $version): string;

    public static function snippet(string $version): string;
}
