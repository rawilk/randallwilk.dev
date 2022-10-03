<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class LaravelSettingsDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'AppSettings.php',
            'composer.json',
        ];
    }

    public static function snippetLanguage(string $version): string
    {
        return 'php';
    }

    public static function snippet(string $version): string
    {
        return <<<'PHP'
        public function store(Request $request)
        {
            Settings::set('app.name', $request->appName);
            Settings::set('app.timezone', $request->appTimezone);
        }
        PHP;
    }
}
