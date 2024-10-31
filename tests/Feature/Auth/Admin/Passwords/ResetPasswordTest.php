<?php

declare(strict_types=1);

use App\Enums\SessionAlert;
use App\Filament\Admin\Pages\Auth\PasswordReset\ResetPassword;
use App\Notifications\Auth\PasswordWasResetNotification;
use Illuminate\Support\Facades\Password as PasswordFacade;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->fakeIpLocation();

    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->freezeSecond();
});

it('renders', function () {
    $user = adminUser();

    $token = getPasswordResetToken($user);

    get(passwordResetUrl(token: $token, email: $user->email))
        ->assertOk()
        ->assertSeeLivewire(ResetPassword::class);
});

it('can be accessed by authenticated users', function () {
    actingAs($user = adminUser());

    $token = getPasswordResetToken($user);

    get(passwordResetUrl(token: $token, email: $user->email))
        ->assertOk()
        ->assertSeeLivewire(ResetPassword::class);
});

it('resets a users password', function () {
    Notification::fake();

    $user = adminUser();

    $token = getPasswordResetToken($user);

    livewire(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->set('password', 'new-password')
        ->call('resetPassword')
        ->assertSuccessful()
        ->assertHasNoFormErrors()
        ->assertRedirect($this->panel->getLoginUrl())
        ->assertSessionHas(SessionAlert::Success->value, __(PasswordFacade::PASSWORD_RESET));

    expect($user->email)->toBeMissingFromPasswordResets();

    $this->assertGuest();

    expect(auth()->attempt([
        'email' => $user->email,
        'password' => 'new-password',
    ]))->toBeTrue();

    Notification::assertSentTo($user, PasswordWasResetNotification::class);
});

test('a logged in user must re-authenticate after a password reset', function () {
    Notification::fake();

    actingAs($user = adminUser(['password' => 'old-password']));

    $token = getPasswordResetToken($user);

    // We're also going to verify that tokens can be used all the way until the last possible second.
    $this->travelTo(now()->addMinutes(config('auth.passwords.users.expire')));

    livewire(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->set('password', 'new-password')
        ->call('resetPassword')
        ->assertRedirect($this->panel->getLoginUrl());

    $this->assertGuest();

    expect('new-password')->toBePasswordFor($user->refresh());
});

it('requires a password', function () {
    $user = adminUser();

    $token = getPasswordResetToken($user);

    livewire(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->call('resetPassword')
        ->assertHasFormErrors(['password' => 'required']);
});

test('email is required', function () {
    Notification::fake();

    $user = adminUser();

    $token = getPasswordResetToken($user);

    livewire(ResetPassword::class, ['token' => $token, 'email' => ''])
        ->fillForm([
            'password' => 'new-password',
        ])
        ->call('resetPassword')
        ->assertNoRedirect()
        ->assertSeeText(__(PasswordFacade::INVALID_USER));

    Notification::assertNothingSent();
});

test('a valid token is required', function () {
    Notification::fake();

    $user = adminUser();

    getPasswordResetToken($user);

    livewire(ResetPassword::class, ['token' => Str::random(), 'email' => $user->email])
        ->fillForm([
            'password' => 'new-password',
        ])
        ->call('resetPassword')
        ->assertNoRedirect()
        ->assertSeeText(__(PasswordFacade::INVALID_TOKEN));

    Notification::assertNothingSent();
});

test('expired links are forbidden', function () {
    $user = adminUser();

    $token = getPasswordResetToken($user);

    $url = passwordResetUrl($token, $user->email);

    $this->travelTo(now()->addMinutes(config('auth.passwords.users.expire'))->addSecond());

    get($url)
        ->assertForbidden();
});

test('expired tokens cannot reset a password', function () {
    Notification::fake();

    $user = adminUser(['password' => 'secret']);

    $token = getPasswordResetToken($user);

    $this->travelTo(now()->addMinutes(config('auth.passwords.users.expire'))->addSecond());

    livewire(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->set('password', 'new-password')
        ->call('resetPassword')
        ->assertNoRedirect()
        ->assertSeeText(__(PasswordFacade::INVALID_TOKEN));

    expect('secret')->toBePasswordFor($user->refresh());

    Notification::assertNothingSent();
});
