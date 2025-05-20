<?php

declare(strict_types=1);

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

arch()->expect('App\Jobs')
    ->classes()
    ->toHaveSuffix('Job');

arch()->expect('App\Jobs')
    ->classes
    ->toImplement(ShouldQueue::class)
    ->toUse([
        Dispatchable::class,
        InteractsWithQueue::class,
        Queueable::class,
    ]);

arch()->expect('App\Jobs')
    ->classes
    ->toHaveMethod('handle');
