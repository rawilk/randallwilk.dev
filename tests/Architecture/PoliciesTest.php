<?php

declare(strict_types=1);

use Illuminate\Auth\Access\HandlesAuthorization;

arch()->expect('App\Policies')
    ->classes()
    ->toHaveSuffix('Policy');

arch()->expect('App\Policies')
    ->classes()
    ->toExtendNothing()
    ->toUse(HandlesAuthorization::class);
