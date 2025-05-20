<?php

declare(strict_types=1);

use Illuminate\View\Component;

arch()->expect('App\View\Components')
    ->classes()
    ->toExtend(Component::class);

arch()->expect('App\View\Components')
    ->classes()
    ->toHaveMethod('render');

arch()->expect('App\View\Components\Concerns')->toBeTraits();
