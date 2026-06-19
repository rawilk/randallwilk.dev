<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();

arch('custom strict')
    ->expect('App')
    ->toUseStrictTypes()
    ->toUseStrictEquality()
    ->classes()->not->toBeFinal();

arch('strict preset globals')
    ->expect(['sleep', 'usleep'])
    ->not->toBeUsed();

arch('traits')
    ->expect([
        'App\Concerns',
        'App\*\Concerns',
        'App\*\*\Concerns',
    ])
    ->toBeTraits();
