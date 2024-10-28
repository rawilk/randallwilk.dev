<?php

declare(strict_types=1);

namespace App\Filament\Plugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class EnvironmentIndicatorPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'randallwilk-env';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE, function () {
            if (! auth()->user()->isAdmin()) {
                return '';
            }

            $environment = app()->environment();

            $color = match ($environment) {
                'production' => Color::Green,
                'staging' => Color::Yellow,
                default => Color::Pink,
            };

            return new HtmlString(Blade::render(<<<'HTML'
            <x-filament::badge :color="$color">
                {{ $environment }}
            </x-filament::badge>
            HTML, [
                'color' => $color,
                'environment' => ucfirst($environment),
            ]));
        });
    }

    public function boot(Panel $panel): void
    {
    }
}
