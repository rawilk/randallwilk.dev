<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

pest()->extend(Tests\TestCase::class)
    ->in(
        'Feature',
        'Unit',
    );

pest()->uses(
    LazilyRefreshDatabase::class,
)->in(
    'Feature',
);
