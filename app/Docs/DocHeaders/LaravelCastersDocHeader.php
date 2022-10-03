<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class LaravelCastersDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'User.php',
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
        class User extends Model
        {
            protected $casts = [
                'password' => Password::class,
                'name' => Name::class,
            ];
        }
        PHP;
    }
}
