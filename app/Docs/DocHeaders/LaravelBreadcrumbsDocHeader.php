<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

class LaravelBreadcrumbsDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'breadcrumbs.php',
            'layout.blade.php',
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
        Breadcrumbs::for('home', function (Generator $trail) {
            $trail->push('Home', route('home'));
        });

        Breadcrumbs::for('about', function (Generator $trail) {
            $trail->parent('home')->push('About', route('about'));
        });
        PHP;
    }
}
