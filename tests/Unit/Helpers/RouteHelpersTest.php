<?php

declare(strict_types=1);

use function Pest\Laravel\actingAs;

test('a super admin user has an admin panel route for the default login redirect', function () {
    $this->artisan('db:seed');

    actingAs(superAdmin());

    expect(defaultLoginRedirect())->toBe(route('admin.dashboard'));
});

test('a normal user will get the profile route for the default login redirect', function () {
    $this->artisan('db:seed');

    actingAs(regularUser());

    expect(defaultLoginRedirect())->toBe(route('profile.show'));
});
