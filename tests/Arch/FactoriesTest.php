<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;

arch()
    ->expect('Database\Factories')
    ->toExtend(Factory::class)
    ->ignoring('Database\Factories\Concerns')
    ->toHaveMethod('definition')
    ->ignoring('Database\Factories\Concerns')
    ->toHaveSuffix('Factory')
    ->ignoring('Database\Factories\Concerns')
    ->toOnlyBeUsedIn([
        'App\Models',
    ]);
