<?php

declare(strict_types=1);

use function App\Helpers\homeRoute;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('logs an authenticated user out', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(homeRoute());

    expect(Auth::check())->toBeFalse();
});

test('an unauthenticated user cannot logout', function () {
    post(route('logout'))
        ->assertRedirect(route('login'));

    expect(Auth::check())->toBeFalse();
});
