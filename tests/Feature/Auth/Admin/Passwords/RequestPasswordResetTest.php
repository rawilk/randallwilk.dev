<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\PasswordReset\RequestPasswordReset;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\ResetPasswordInvalidUser;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);
});

it('renders', function () {
    get($this->panel->getRequestPasswordResetUrl())
        ->assertOk()
        ->assertSeeLivewire(RequestPasswordReset::class)
        ->assertSee($this->panel->getLoginUrl());
});

it('can be viewed by authenticated users', function () {
    actingAs(adminUser())
        ->get($this->panel->getRequestPasswordResetUrl())
        ->assertOk()
        ->assertSeeLivewire(RequestPasswordReset::class)
        // We shouldn't see the back to login link when we're authenticated.
        ->assertDontSee($this->panel->getLoginUrl());
});

it('sends a reset link to valid users', function () {
    $user = adminUser();

    Notification::fake();

    $this->withoutDefer();
    $this->freezeSecond();

    livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('request')
        ->assertSee(
            str(__('pages/auth/request-password-reset.alerts.sent.description', ['email' => e($user->email)]))->markdown()->toHtmlString()
        )
        ->assertFormSet(['email' => null]);

    expect($user->email)->toHavePasswordResetToken();

    Notification::assertSentTo($user, function (ResetPassword $notification) use ($user) {
        // Make sure the correct url is being sent in the email.
        $expectedUrl = URL::temporarySignedRoute(
            name: $this->panel->generateRouteName('auth.password-reset.reset'),
            expiration: now()->addMinutes(config('auth.passwords.users.expire')),
            parameters: [
                'token' => $notification->token,
                'email' => $user->getEmailForPasswordReset(),
            ],
        );

        $actualUrl = (fn () => $this->resetUrl($user))->call($notification);

        expect($actualUrl)->toBe($expectedUrl, 'Password reset link does not match expected url');

        return true;
    });
});

test('email address is required', function () {
    livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => '',
        ])
        ->call('request')
        ->assertHasFormErrors(['email' => 'required']);
});

it('requires a valid email address', function () {
    livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => 'bad-email',
        ])
        ->call('request')
        ->assertHasFormErrors(['email' => 'email']);
});

test('a user can reset the form to send a link again after the form has been submitted', function () {
    $user = adminUser();

    Notification::fake();

    livewire(RequestPasswordReset::class)
        ->fillForm(['email' => $user->email])
        ->call('request')
        ->assertSet('email', $user->email)
        ->call('resend')
        ->assertSet('email', null);
});

test('an email will be sent to non-registered users', function () {
    Notification::fake();

    $this->withoutDefer();

    livewire(RequestPasswordReset::class)
        ->fillForm(['email' => 'not-exists@example.com'])
        ->call('request')
        ->assertSuccessful()
        ->assertSet('email', 'not-exists@example.com');

    expect('not-exists@example.com')->toBeMissingFromPasswordResets();

    Notification::assertCount(1);

    Notification::assertSentOnDemand(
        ResetPasswordInvalidUser::class,
        function (ResetPasswordInvalidUser $notification, array $channels, object $notifiable) {
            $loginUrl = (fn () => $this->loginUrl)->call($notification);

            expect($notifiable->routes['mail'])->toBe('not-exists@example.com')
                ->and($loginUrl)->toBe($this->panel->getLoginUrl());

            return true;
        },
    );
});
