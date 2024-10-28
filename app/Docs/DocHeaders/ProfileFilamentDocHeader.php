<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

class ProfileFilamentDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'AdminPanelProvider.php',
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
        $panel
            ->plugin(
                ProfileFilamentPlugin::make()
                    ->useMfaMiddleware(false)
            )
        PHP;
    }
}
