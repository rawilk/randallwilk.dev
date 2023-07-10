<?php

declare(strict_types=1);

use App\Models\User\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Email;

test('can view the password reset page', function () {
    get(route('password.request'))
        ->assertSuccessful()
        ->assertSeeLivewire('password.email');
});

test('email address is required', function () {
    livewire(Email::class)
        ->call('sendPasswordResetLink')
        ->assertHasErrors(['email' => 'required']);
});

it('requires a valid email address', function () {
    livewire(Email::class)
        ->set('email', 'bad-email')
        ->call('sendPasswordResetLink')
        ->assertHasErrors(['email' => 'email']);
});

it('sends an email for existing application users', function () {
    $user = User::factory()->create();

    Mail::fake();

    livewire(Email::class)
        ->set('email', $user->email)
        ->call('sendPasswordResetLink')
        ->assertSet('emailSent', true);

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});
