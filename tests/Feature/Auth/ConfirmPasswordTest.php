<?php

declare(strict_types=1);

use App\Models\User\User;
use Illuminate\Support\Facades\Route;
use Rawilk\LaravelBase\Http\Livewire\Auth\ConfirmPassword;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\withSession;
use function Pest\Livewire\livewire;

beforeEach(function () {
    Route::get('/must-be-confirmed', fn () => 'You must be confirmed to see this page.')
        ->middleware(['web', 'password.confirm']);
});

test('a user must confirm their password before visiting a protected page', function () {
    actingAs(User::factory()->create())
        ->get('/must-be-confirmed')
        ->assertRedirect(route('password.confirm'));

    followingRedirects()->get('/must-be-confirmed')->assertSuccessful();
});

it('requires a correct password to confirm it', function () {
    actingAs(User::factory()->create(['password' => 'secret']));

    livewire(ConfirmPassword::class)
        ->set('password', 'incorrect')
        ->call('confirm')
        ->assertHasErrors('password');
});

it('redirects a user to the intended page after confirming their password', function () {
    actingAs(User::factory()->create(['password' => 'secret']));
    withSession(['url.intended' => '/must-be-confirmed']);

    livewire(ConfirmPassword::class)
        ->set('password', 'secret')
        ->call('confirm')
        ->assertRedirect('/must-be-confirmed');
});
