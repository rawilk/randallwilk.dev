<?php

declare(strict_types=1);

arch()->expect('App\Exceptions')
    ->classes()
    ->toImplement('Throwable')
    ->ignoring('App\Exceptions\Handler');
