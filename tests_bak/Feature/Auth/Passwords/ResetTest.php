<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Reset;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('can see the password reset page', function () {
    $user = User::factory()->create();

    $token = getPasswordResetToken($user);

    get(route('password.reset', [
        'email' => $user->email,
        'token' => $token,
    ]))->assertSuccessful()->assertSeeLivewire('password.reset');
});

it("resets a user's password", function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $token = getPasswordResetToken($user);

    livewire(Reset::class, ['token' => $token, 'email' => $user->email])
        ->set('password', 'new-password')
        ->call('resetPassword');

    expect(Auth::attempt([
        'email' => $user->email,
        'password' => 'new-password',
    ]))->toBeTrue();

    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

it('requires a token', function () {
    livewire(Reset::class, ['token' => ''])
        ->call('resetPassword')
        ->assertHasErrors(['token' => 'required']);
});

it('requires an email', function () {
    livewire(Reset::class, ['token' => Str::random()])
        ->set('email', '')
        ->set('password', 'new-password')
        ->call('resetPassword')
        ->assertHasErrors(['email' => 'required']);
});

it('requires a valid email', function () {
    livewire(Reset::class, ['token' => Str::random()])
        ->set('email', 'email')
        ->set('password', 'new-password')
        ->call('resetPassword')
        ->assertHasErrors(['email' => 'email']);
});

it('requires a new password', function () {
    livewire(Reset::class, ['token' => Str::random()])
        ->set('email', 'email@example.com')
        ->set('password', '')
        ->call('resetPassword')
        ->assertHasErrors(['password' => 'required']);
});

// Helpers...
function getPasswordResetToken($user): string
{
    $token = Str::random();

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    return $token;
}
