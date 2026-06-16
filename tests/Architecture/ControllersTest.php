<?php

declare(strict_types=1);

arch()
    ->expect([
        'App\Http\Controllers',
    ])
    ->classes()
    ->toHaveSuffix('Controller')
    ->toExtendNothing()
    ->not->toBeUsed();

arch()
    ->expect('App\Http')
    ->toOnlyBeUsedIn('App\Http')
    ->ignoring([
        'App\Docs',
    ]);
