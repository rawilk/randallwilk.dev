<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;

arch()->expect('App\Models')
    ->classes()
    ->toExtend(Model::class)
    ->ignoring('App\Models\Scopes');

arch()->expect('App\Models')
    ->classes()
    ->not->toHaveSuffix('Model');

arch()->expect('App')
    ->classes()
    ->not->toExtend(Model::class)
    ->ignoring([
        'App\Models',
    ]);

arch()->expect('App\Models\Concerns')->toBeTraits();
