<?php

declare(strict_types=1);

use Illuminate\Console\Command;

arch()->expect('App\Console\Commands')
    ->classes()
    ->toExtend(Command::class);

arch()->expect('App\Console\Commands')
    ->classes()
    ->toHaveSuffix('Command');

arch()->expect('App\Console\Commands')
    ->classes()
    ->toHaveMethod('handle');

arch()->expect('App')
    ->classes()
    ->not->toExtend(Command::class)
    ->ignoring('App\Console\Commands');
