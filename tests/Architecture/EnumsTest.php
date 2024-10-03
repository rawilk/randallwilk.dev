<?php

declare(strict_types=1);

arch()->expect('App\Enums')
    ->toBeEnums()
    ->ignoring('App\Enums\Concerns');

arch()->expect('App\Enums')
    ->enums()
    ->not->toHaveSuffix('Enum');
