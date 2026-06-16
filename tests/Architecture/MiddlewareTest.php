<?php

declare(strict_types=1);

arch()
    ->expect('App\Http\Middleware')
    ->toHaveMethod('handle')
    ->toUse(Illuminate\Http\Request::class)
    ->not->toBeUsed();
