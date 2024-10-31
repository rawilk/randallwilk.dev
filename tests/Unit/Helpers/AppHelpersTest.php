<?php

declare(strict_types=1);

use App\Models\User;

use function App\Helpers\appTimezone;
use function App\Helpers\formatPageTitle;
use function App\Helpers\userTimezone;

test('it can get the configured application timezone', function () {
    config([
        'randallwilk.timezone' => 'America/Chicago',
    ]);

    expect(appTimezone())->toBe('America/Chicago');
});

it('can get a users timezone', function () {
    config([
        'randallwilk.timezone' => 'America/Chicago',
    ]);

    $user = User::factory()->make(['timezone' => 'America/New_York']);

    expect(userTimezone($user))->toBe('America/New_York');

    $user->timezone = null;

    expect(userTimezone($user))->toBe('America/Chicago');
});

it('can format a page title', function () {
    $title = formatPageTitle('one', 'two', 'three');

    expect($title)->toBe('one - two - three');
});
