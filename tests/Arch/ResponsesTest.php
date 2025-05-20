<?php

declare(strict_types=1);

arch()->expect('App\Responses')
    ->classes()
    ->toHaveSuffix('Response');
