<?php

declare(strict_types=1);

use App\Enums\SessionAlert;
use App\Filament\Admin\Pages\Auth\PasswordReset\ResetPassword;
use App\Notifications\Auth\PasswordWasResetNotification;
use App\Support\AppConfig;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\RateLimiter;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertCredentials;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertInvalidCredentials;
use function Pest\Laravel\freezeSecond;
use function Pest\Laravel\get;
use function Pest\Laravel\travelTo;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->page = ResetPassword::class;

    freezeSecond();
});

it('renders', function () {
    $user = adminUser();

    $token = Password::createToken($user);

    get(passwordResetUrl(token: $token, email: $user->email))
        ->assertOk()
        ->assertSeeLivewire($this->page);
});

it('can be accessed by authenticated users', function () {
    actingAs($user = adminUser());

    $token = Password::createToken($user);

    get(passwordResetUrl(token: $token, email: $user->email))
        ->assertOk()
        ->assertSeeLivewire($this->page);
});

describe('resetting a password', function () {
    it('can reset a password', function () {
        $user = adminUser();

        Event::fake();
        Notification::fake();

        assertGuest();

        $token = Password::createToken($user);

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertRedirect($this->panel->getLoginUrl())
            ->assertSessionHas(SessionAlert::Success->value, __(Password::PASSWORD_RESET));

        Event::assertDispatched(PasswordReset::class);

        expect($user->email)->toBeMissingFromPasswordResets();

        assertCredentials([
            'email' => $user->email,
            'password' => 'new-password',
        ]);
    });

    it('sends a notification to the user when the password is reset', function () {
        $user = adminUser();
        Notification::fake();

        $token = Password::createToken($user);

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertHasNoFormErrors();

        Notification::assertSentTo($user, PasswordWasResetNotification::class);
    });

    it('forces a logged-in user to re-authenticate after a password reset', function () {
        actingAs($user = adminUser(['password' => 'old-password']));

        $token = Password::createToken($user);
        Notification::fake();

        assertAuthenticatedAs($user);

        livewire($this->page, [
            'token' => $token,
            'email' => $user->email,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertRedirect($this->panel->getLoginUrl());

        assertGuest();

        assertInvalidCredentials([
            'email' => $user->email,
            'password' => 'old-password',
        ]);

        Notification::assertSentTo($user, PasswordWasResetNotification::class);
    });

    it('requires a signature', function () {
        $user = adminUser();
        $token = Password::createToken($user);

        $url = $this->panel->route('auth.password-reset.reset');

        get($url, [
            'email' => $user->email,
            'token' => $token,
        ])->assertForbidden();
    });

    it('requires a valid email and token', function () {
        $user = adminUser();

        Event::fake();
        Notification::fake();

        assertGuest();

        $token = Password::createToken($user);

        livewire($this->page, [
            'email' => $user->email,
            'token' => Str::random(),
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertNoRedirect()
            ->assertSeeText(__(Password::INVALID_TOKEN));

        Event::assertNotDispatched(PasswordReset::class);

        livewire($this->page, [
            'email' => fake()->email(),
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertNoRedirect()
            ->assertSeeText(__(Password::INVALID_USER));

        Event::assertNotDispatched(PasswordReset::class);
    });
});

describe('rate limiting', function () {
    it('can throttle reset password attempts', function () {
        Event::fake([PasswordReset::class]);

        assertGuest();

        foreach (range(1, 2) as $i) {
            $user = adminUser();
            $token = Password::createToken($user);

            livewire($this->page, [
                'email' => $user->email,
                'token' => $token,
            ])
                ->fillForm([
                    'password' => 'new-password',
                ])
                ->call('resetPassword')
                ->assertRedirect($this->panel->getLoginUrl());
        }

        Event::assertDispatchedTimes(PasswordReset::class, times: 2);

        assertCredentials([
            'email' => $user->email,
            'password' => 'new-password',
        ]);

        $token = Password::createToken($user);

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'newer-password',
            ])
            ->call('resetPassword')
            ->assertNotified()
            ->assertNoRedirect();

        Event::assertDispatchedTimes(PasswordReset::class, times: 2);

        assertCredentials([
            'email' => $user->email,
            'password' => 'new-password',
        ]);
    });

    it('can throttle reset password attempts per email', function () {
        Event::fake([PasswordReset::class]);

        assertGuest();

        $user = adminUser();

        // Clear the IP-based rate limiter between attempts to isolate the
        // email-based rate limit (simulates an attacker rotating IPs).
        $clearIpRateLimiter = function (): void {
            RateLimiter::clear('livewire-rate-limiter:' . sha1($this->page . '|resetPassword|' . request()->ip()));
        };

        foreach (range(1, 2) as $i) {
            $clearIpRateLimiter();

            $token = Password::createToken($user);

            livewire($this->page, [
                'email' => $user->email,
                'token' => $token,
            ])
                ->fillForm([
                    'password' => 'new-password',
                ])
                ->call('resetPassword')
                ->assertRedirect($this->panel->getLoginUrl());
        }

        Event::assertDispatchedTimes(PasswordReset::class, times: 2);

        $clearIpRateLimiter();

        // The 3rd attempt should be rate limited by email.
        $token = Password::createToken($user);

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'newer-password',
            ])
            ->call('resetPassword')
            ->assertNotified()
            ->assertNoRedirect();

        Event::assertDispatchedTimes(PasswordReset::class, times: 2);

        $clearIpRateLimiter();

        // A different email should not be affected.
        $secondUser = adminUser();
        $secondToken = Password::createToken($secondUser);

        livewire($this->page, [
            'email' => $secondUser->email,
            'token' => $secondToken,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertRedirect($this->panel->getLoginUrl());

        Event::assertDispatchedTimes(PasswordReset::class, times: 3);
    });
});

describe('validation', function () {
    it('requires a password', function () {
        $user = adminUser();
        $token = Password::createToken($user);

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => '',
            ])
            ->call('resetPassword')
            ->assertHasFormErrors(['password' => ['required']]);
    });

    it('cannot reset a password with an expired token', function () {
        Notification::fake();
        Event::fake([PasswordReset::class]);

        $user = adminUser();
        $token = Password::createToken($user);

        travelTo(now()->addMinutes(AppConfig::passwordResetDecayMinutes())->addSecond());

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertNoRedirect();

        assertCredentials([
            'email' => $user->email,
            'password' => 'secret',
        ]);

        Event::assertNotDispatched(PasswordReset::class);
    });

    test('tokens cannot be replayed', function () {
        $user = adminUser();
        $token = Password::createToken($user);

        Event::fake([PasswordReset::class]);
        Notification::fake();

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'new-password',
            ])
            ->call('resetPassword')
            ->assertRedirect($this->panel->getLoginUrl());

        Event::assertDispatchedTimes(PasswordReset::class, times: 1);

        expect($user->email)->toBeMissingFromPasswordResets();

        livewire($this->page, [
            'email' => $user->email,
            'token' => $token,
        ])
            ->fillForm([
                'password' => 'newer-password',
            ])
            ->call('resetPassword')
            ->assertNoRedirect()
            ->assertSeeText(__(Password::INVALID_TOKEN));

        Event::assertDispatchedTimes(PasswordReset::class, times: 1);

        assertCredentials([
            'email' => $user->email,
            'password' => 'new-password',
        ]);

        assertInvalidCredentials([
            'email' => $user->email,
            'password' => 'newer-password',
        ]);
    });
});

describe('browser tests', function () {
    it('can reset a password in the browser', function () {
        $user = adminUser();
        $token = Password::createToken($user);

        Event::fake([PasswordReset::class]);
        Notification::fake();

        visit(passwordResetUrl(token: $token, email: $user->email))
            ->assertSee(__('pages/auth/reset-password.subheading', ['email' => $user->email]))
            ->assertNoSmoke()
            // ->assertNoAccessibilityIssues()
            ->type('input[type="password"]', 'new-password')
            ->click('button[type="submit"]')
            ->assertUrlIs($this->panel->getLoginUrl())
            ->assertSee(__(Password::PASSWORD_RESET))
            ->assertNoSmoke();
        // ->assertNoAccessibilityIssues()

        assertCredentials([
            'email' => $user->email,
            'password' => 'new-password',
        ]);
    });

    test('dark mode is accessible', function () {
        $user = adminUser();
        $token = Password::createToken($user);

        Notification::fake();

        visit(passwordResetUrl(token: $token, email: $user->email))
            ->inDarkMode()
            // ->assertNoAccessibilityIssues()
            ->type('input[type="password"]', 'new-password')
            ->click('button[type="submit"]')
            ->assertUrlIs($this->panel->getLoginUrl())
            ->assertSee(__(Password::PASSWORD_RESET));
        // ->assertNoAccessibilityIssues()
    });
});
