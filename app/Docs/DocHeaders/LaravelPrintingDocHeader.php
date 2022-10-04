<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class LaravelPrintingDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'PrintJob.php',
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
        Printing::newPrintTask()
            ->printer($printerId)
            ->file('my-file.pdf')
            ->send();
        PHP;
    }
}
