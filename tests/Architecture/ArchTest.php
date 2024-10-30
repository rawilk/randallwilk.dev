<?php

declare(strict_types=1);

arch()->preset()->security();

arch()->expect('App')->toUseStrictTypes();
arch()->expect('App')->toUseStrictEquality();
arch()->expect('App')
    ->classes()
    ->not->toBeFinal();

// Not using the `laravel` preset because I don't want to strictly adhere to every single one
// of their architecture styles.

arch()->expect('App\Concerns')
    ->toBeTraits();

arch()->expect([
    'dd',
    'ddd',
    'dump',
    'env',
    'exit',
    'ray',

    // strict preset
    'sleep',
    'usleep',

    // 'time()' is difficult to test; now()->timestamp should be used instead
    'time',
])->not->toBeUsed();
