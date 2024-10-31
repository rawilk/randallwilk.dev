<?php

declare(strict_types=1);

use Illuminate\Console\Command;

arch()
    ->expect('App\Console\Commands')
    ->toExtend(Command::class)
    ->toHaveSuffix('Command')
    ->toHaveMethod('handle')
    ->toImplementNothing()
    ->not->toBeUsed();
