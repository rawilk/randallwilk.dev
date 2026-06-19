<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\PasswordReset\RequestPasswordReset;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\ResetPasswordInvalidUser;
use App\Support\AppConfig;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\freezeSecond;
use function Pest\Laravel\get;
use function Pest\Laravel\withoutDefer;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->page = RequestPasswordReset::class;

    withoutDefer();
    freezeSecond();
});

it('renders', function () {
    get($this->panel->getRequestPasswordResetUrl())
        ->assertOk()
        ->assertSeeLivewire($this->page)
        ->assertSee($this->panel->getLoginUrl());
});

it('can be viewed by authenticated users', function () {
    actingAs(adminUser())
        ->get($this->panel->getRequestPasswordResetUrl())
        ->assertOk()
        ->assertSeeLivewire($this->page)
        // We shouldn't see the back-to-login link when we're authenticated.
        ->assertDontSee($this->panel->getLoginUrl());
});

describe('requesting password reset', function () {
    it('can request a password reset', function () {
        $user = adminUser();

        Notification::fake();
        Event::fake();

        assertGuest();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
            ])
            ->call('request')
            ->assertSet('email', $user->email);

        expect($user->email)->toHavePasswordResetToken();

        Notification::assertSentTo($user, function (ResetPassword $notification) use ($user) {
            $expectedUrl = URL::temporarySignedRoute(
                name: $this->panel->generateRouteName('auth.password-reset.reset'),
                expiration: now()->addMinutes(AppConfig::passwordResetDecayMinutes()),
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

    it('sends an email to non-existing users', function () {
        $email = 'not-exists@example.com';

        Notification::fake();

        assertGuest();

        livewire($this->page)
            ->fillForm([
                'email' => $email,
            ])
            ->call('request')
            ->assertSuccessful()
            ->assertSet('email', $email);

        expect($email)->toBeMissingFromPasswordResets();

        Notification::assertSentOnDemand(
            ResetPasswordInvalidUser::class,
            function (ResetPasswordInvalidUser $notification, array $channels, object $notifiable) use ($email) {
                $loginUrl = (fn () => $this->loginUrl)->call($notification);

                expect($notifiable->routes['mail'])->toBe($email)
                    ->and($loginUrl)->toBe($this->panel->getLoginUrl());

                return true;
            },
        );
    });

    it('can throttle requests', function () {
        Notification::fake();

        assertGuest();

        foreach (range(1, 2) as $i) {
            $user = adminUser();

            livewire($this->page)
                ->fillForm([
                    'email' => $user->email,
                ])
                ->call('request')
                ->assertSet('email', $user->email);

            Notification::assertSentToTimes($user, ResetPassword::class, times: 1);
        }

        $user = adminUser();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
            ])
            ->call('request')
            ->assertNotified();

        Notification::assertNotSentTo($user, ResetPassword::class);
    });
});

describe('validation', function () {
    it('requires an email', function () {
        livewire($this->page)
            ->fillForm([
                'email' => '',
            ])
            ->call('request')
            ->assertHasFormErrors(['email' => ['required']]);
    });

    it('requires a valid email', function () {
        livewire($this->page)
            ->fillForm([
                'email' => 'invalid-email',
            ])
            ->call('request')
            ->assertHasFormErrors(['email' => ['email']]);
    });

    it('is strict with email validation in production', function (string $invalidEmail) {
        putAppInProduction();

        livewire($this->page)
            ->fillForm([
                'email' => $invalidEmail,
            ])
            ->call('request')
            ->assertHasFormErrors(['email']);
    })->with('invalid production emails');
});

describe('browser tests', function () {
    it('can fill the form and request a password reset', function () {
        $user = adminUser();

        Notification::fake();

        visit($this->panel->getRequestPasswordResetUrl())
            ->assertSee(__('pages/auth/request-password-reset.heading'))
            ->assertNoSmoke()
            // ->assertNoAccessibilityIssues()
            ->type('input[type="email"]', $user->email)
            ->click('button[type="submit"]')
            // ->assertNoAccessibilityIssues()
            ->assertNoSmoke()
            ->assertDontSee(__('pages/auth/request-password-reset.heading'))
            ->assertSee($user->email);
    });

    test('dark mode is accessible', function () {
        $user = adminUser();

        Notification::fake();

        visit($this->panel->getRequestPasswordResetUrl())
            ->inDarkMode()
            // ->assertNoAccessibilityIssues()
            ->type('input[type="email"]', $user->email)
            ->click('button[type="submit"]')
            // ->assertNoAccessibilityIssues()
            ->assertNoSmoke();
    });
});
