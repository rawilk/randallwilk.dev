<?php

declare(strict_types=1);

use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Rawilk\LaravelBase\Http\Livewire\Auth\Login;

use function App\Helpers\defaultLoginRedirect;
use function App\Helpers\homeRoute;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('can view the login page', function () {
    get(route('login'))
        ->assertSuccessful()
        ->assertSeeLivewire('login');
});

it('redirects if user is already logged in', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('login'))
        ->assertRedirect(homeRoute());
});

test('a user can login', function () {
    $user = User::factory()->create(['password' => 'secret']);

    livewire(Login::class)
        ->set('email', $user->email)
        ->set('password', 'secret')
        ->call('login');

    $this->assertAuthenticatedAs($user);

    expect($user->email)->toBe(Auth::user()->email);
});

it('redirects the user to the home page after login', function () {
    $user = User::factory()->create(['password' => 'secret']);

    livewire(Login::class)
        ->set('email', $user->email)
        ->set('password', 'secret')
        ->call('login')
        ->assertRedirect(defaultLoginRedirect());
});

test('email is required', function () {
    livewire(Login::class)
        ->set('password', 'secret')
        ->call('login')
        ->assertHasErrors(['email' => 'required']);
});

test('password is required', function () {
    livewire(Login::class)
        ->set('email', 'email@example.com')
        ->call('login')
        ->assertHasErrors(['password' => 'required']);
});

it('shows message after failed login attempt', function () {
    $user = User::factory()->create(['password' => 'secret']);

    livewire(Login::class)
        ->set('email', $user->email)
        ->set('password', 'bad-password')
        ->call('login')
        ->assertHasErrors('email');

    expect(Auth::check())->toBeFalse();
});
