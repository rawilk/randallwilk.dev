<?php

declare(strict_types=1);

use App\Support\Database\Redaction\BaseRedactor;
use App\Support\Database\Redaction\Contracts\Redactor;

arch()
    ->expect('App\Support\Database\Redaction')
    ->toBeClasses()
    ->ignoring('App\Support\Database\Redaction\Contracts');

arch()
    ->expect('App\Support\Database\Redaction\Contracts')
    ->toBeInterfaces();

arch()
    ->expect('App\Support\Database\Redaction')
    ->toExtend(BaseRedactor::class)
    ->ignoring([
        'App\Support\Database\Redaction\Contracts',
    ])
    ->toImplement(Redactor::class);

arch()
    ->expect('App\Support\Database\Redaction')
    ->classes()
    ->toHaveSuffix('Redactor');
