<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

arch()->expect('App\Providers')
    ->toHaveSuffix('ServiceProvider')
    ->ignoring('App\Providers\Filament');

arch()->expect('App\Providers')->toHaveSuffix('Provider');

arch()->expect('App\Providers')
    ->toExtend(ServiceProvider::class);

arch()->expect('App')
    ->classes()
    ->not->toExtend(ServiceProvider::class)
    ->ignoring('App\Providers');

arch()->expect('App\Providers')
    ->not
    ->toBeUsed()
    ->ignoring('App\Providers');
