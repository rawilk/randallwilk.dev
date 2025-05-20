<?php

declare(strict_types=1);

use Filament\Contracts\Plugin;

arch()->expect('App\Filament\Plugins')
    ->classes()
    ->toImplement(Plugin::class)
    ->toHaveSuffix('Plugin');

arch()->expect('App\Filament\Concerns')->toBeTraits();
