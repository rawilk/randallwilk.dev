<?php

declare(strict_types=1);

arch()
    ->expect('App\Exceptions')
    ->toImplement('Throwable');
