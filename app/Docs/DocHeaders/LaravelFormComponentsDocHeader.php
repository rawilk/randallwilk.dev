<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

class LaravelFormComponentsDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'edit-user.blade.php',
            'composer.json',
        ];
    }

    public static function snippetLanguage(string $version): string
    {
        return 'html';
    }

    public static function snippet(string $version): string
    {
        return <<<'HTML'
        <x-form wire:submit.prevent="save">
            <x-input wire:model.defer="name" />
            <x-email wire:model.defer="email" required />
        </x-form>
        HTML;
    }
}
