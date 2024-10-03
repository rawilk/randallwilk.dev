<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

it('gets the configured application timezone', function () {
    config(['site.timezone' => 'America/Chicago']);

    expect(appTimezone())->toBe('America/Chicago');

    config(['site.timezone' => 'America/New_York']);

    expect(appTimezone())->toBe('America/New_York');
});

it("gets a user's timezone", function () {
    $user = User::factory()->create();

    expect(userTimezone($user))->toBe($user->timezone);

    actingAs($user);

    expect(userTimezone())->toBe($user->timezone);
});

test('userTimezone returns the app timezone as a fallback value', function () {
    config(['site.timezone' => 'America/Chicago']);

    expect(userTimezone())->toBe('America/Chicago');
});
