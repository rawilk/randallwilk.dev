<?php

declare(strict_types=1);

use App\Actions\Auth\LogoutAction;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

it('logs a user out', function () {
    actingAs(User::factory()->create());

    app(LogoutAction::class)();

    assertGuest();
});
