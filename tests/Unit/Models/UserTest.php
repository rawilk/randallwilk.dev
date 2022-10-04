<?php

declare(strict_types=1);

use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Rawilk\LaravelBase\Models\Role;

it("can get a user's full name", function () {
    $user = new User([
        'first_name' => 'John',
        'last_name' => 'Smith',
    ]);

    expect($user->name->full)->toBe('John Smith');
});

it("automatically hashes the user's password", function () {
    $user = new User(['password' => 'foo']);

    expect($user->password)->not()->toBe('foo')
        ->and(Hash::check('foo', $user->password))->toBeTrue();

    $user->password = 'updated';
    expect(Hash::check('foo', $user->password))->toBeFalse()
        ->and(Hash::check('updated', $user->password))->toBeTrue();
});

it('knows if a user is a super admin', function () {
    $this->artisan('db:seed');

    $user = User::factory()->create();

    expect($user->isSuperAdmin())->toBeFalse();

    $user->assignRole(Role::$superAdminName);

    expect($user->fresh()->isSuperAdmin())->toBeTrue();
});
