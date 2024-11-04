<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('knows if it is an admin', function () {
    $user = User::factory()->make(['is_admin' => false]);

    expect($user->isAdmin())->toBeFalse();

    $user->is_admin = true;

    expect($user->isAdmin())->toBeTrue();
});

it('only allows admins to access the admin panel', function () {
    $user = User::factory()->make(['is_admin' => false]);

    $panel = filament()->getPanel('admin');

    expect($user->canAccessPanel($panel))->toBeFalse();

    $user->is_admin = true;

    expect($user->canAccessPanel($panel))->toBeTrue();
});

it('has a name for filament', function () {
    $user = User::factory()->make(['name' => 'Dexter Morgan']);

    expect($user->getFilamentName())->toBe('Dexter Morgan');
});

it('generates the correct context for settings', function () {
    $user = User::factory()->create();

    expect($user->context()->toArray())->toEqualCanonicalizing([
        'model' => $user::class,
        'id' => $user->h_key,
    ]);
});
