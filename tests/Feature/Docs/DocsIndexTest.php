<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('loads the docs', function () {
    get(route('docs'))
        ->assertOk()
        ->assertSee('laravel-settings');
});
