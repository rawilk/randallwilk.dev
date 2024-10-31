<?php

declare(strict_types=1);

arch()->expect('App\Http\Controllers')
    ->classes()
    ->toHaveSuffix('Controller');

arch()->expect('App\Http\Controllers')
    ->classes()
    ->toExtendNothing();

arch()->expect('App')
    ->classes()
    ->not->toHaveSuffix('Controller')
    ->ignoring('App\Http\Controllers');

arch()->expect('App\Http')->toOnlyBeUsedIn('App\Http')->ignoring([
    'App\Docs',
]);
