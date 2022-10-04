<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class LaravelWebauthnDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'TwoFactorChallenge.php',
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
        $valid = Webauthn::validateAssertion($user, Arr::only([
            'id',
            'rawId',
            'response',
            'type',
        ]));

        $valid && auth()->login($user);
        PHP;
    }
}
