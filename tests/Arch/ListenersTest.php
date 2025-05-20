<?php

declare(strict_types=1);

arch()->expect('App\Listeners')
    ->classes()
    ->toHaveSuffix('Listener');

arch()->expect('App\Listeners')
    ->classes
    ->toExtendNothing();

arch()->expect('App\Listeners')
    ->classes
    ->toHaveMethod('handle');

arch()->expect('App')
    ->not->toUse('App\Listeners');
