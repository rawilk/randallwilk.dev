<?php

declare(strict_types=1);

use Livewire\Component;

arch()->expect('App\Livewire')
    ->classes()
    ->toExtend(Component::class);

arch()->expect('App\Livewire')
    ->classes()
    ->not->toHaveSuffix('Component');
