<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;

arch()
    ->expect('Database\Factories')
    ->classes()
    ->toExtend(Factory::class)
    ->toHaveMethod('definition')
    ->toHaveSuffix('Factory')
    ->toOnlyBeUsedIn([
        'App\Models',
    ]);
