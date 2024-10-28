<?php

declare(strict_types=1);

namespace App\Filament\Plugins;

use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Illuminate\Support\Facades\Gate;

class HorizonPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'randallwilk-horizon';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->userMenuItems([
                MenuItem::make()
                    ->label('Horizon')
                    ->visible(fn (): bool => Gate::allows('viewHorizon'))
                    ->icon('svg-horizon')
                    ->url(
                        fn (): string => route('horizon.index'),
                        shouldOpenInNewTab: true,
                    ),
            ]);
    }

    public function boot(Panel $panel): void
    {
    }
}
