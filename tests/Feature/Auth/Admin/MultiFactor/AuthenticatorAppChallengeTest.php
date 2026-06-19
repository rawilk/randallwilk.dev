<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\Login;
use App\Filament\Admin\Pages\Auth\MultiFactorChallenge;
use App\Models\User;
use Rawilk\ProfileFilament\Auth\Multifactor\App\AppAuthenticationProvider;
use Rawilk\ProfileFilament\Auth\Multifactor\App\Events\AuthenticatorAppWasUsed;
use Rawilk\ProfileFilament\Auth\Multifactor\Facades\Mfa as MfaFacade;
use Rawilk\ProfileFilament\Auth\Multifactor\Services\Mfa as MfaService;
use Rawilk\ProfileFilament\Models\AuthenticatorApp;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\freezeSecond;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->loginPage = Login::class;
    $this->challengePage = MultiFactorChallenge::class;
    $this->challengeUrl = $this->panel->route('auth.multi-factor-challenge');

    $this->provider = Rawilk\ProfileFilament\Facades\ProfileFilament::plugin()->getMultiFactorAuthenticationProvider(
        AppAuthenticationProvider::ID,
    );

    freezeSecond();
});

describe('authentication flow', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();
    });

    it('will authenticate the user after a valid challenge code is used', function () {
        MfaFacade::pushChallengedUser($this->user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        $code = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $code,
                ],
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        expect($this->user->authenticatorApps->first()->fresh())->last_used_at->toBe(now());

        assertAuthenticatedAs($this->user);

        Event::assertDispatched(function (AuthenticatorAppWasUsed $event): bool {
            expect($event->user)->toBe($this->user)
                ->and($event->authenticatorApp)->toBe($this->user->authenticatorApps->first());

            return true;
        });
    });

    it('will authenticate the user with one of multiple authenticator apps', function () {
        $user = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory()->count(3))
            ->create();

        /** @var AuthenticatorApp $middleApp */
        $middleApp = $user->authenticatorApps->get(1);

        MfaFacade::pushChallengedUser($user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        $code = $this->provider->getCurrentCode($middleApp->secret);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $code,
                ],
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        expect($middleApp->fresh())->last_used_at->toBe(now())
            ->and($user->authenticatorApps->first()->last_used_at)->toBeNull()
            ->and($user->authenticatorApps->get(2)->last_used_at)->toBeNull();

        assertAuthenticatedAs($user);

        Event::assertDispatched(function (AuthenticatorAppWasUsed $event) use ($middleApp, $user): bool {
            expect($event->user->is($user))->toBeTrue()
                ->and($event->authenticatorApp->is($middleApp))->toBeTrue();

            return true;
        });
    });

    it('will not redirect the user when an invalid challenge code is used', function () {
        MfaFacade::pushChallengedUser($this->user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        $code = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $code === '000000'
                        ? '111111'
                        : '000000',
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->provider->getId()}.code",
            ])
            ->assertNoRedirect();

        assertGuest();

        Event::assertNotDispatched(AuthenticatorAppWasUsed::class);
    });

    it("will not authenticate a user with another user's authenticator app code", function () {
        $otherUser = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();

        MfaFacade::pushChallengedUser($this->user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        $code = $this->provider->getCurrentCode($otherUser->authenticatorApps->first()->secret);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $code,
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->provider->getId()}.code",
            ])
            ->assertNoRedirect();

        expect($this->user->authenticatorApps->first()->fresh())->last_used_at->toBeNull()
            ->and($otherUser->authenticatorApps->first()->fresh())->last_used_at->toBeNull();

        assertGuest();

        Event::assertNotDispatched(AuthenticatorAppWasUsed::class);
    });

    it('will not authenticate a user with app code when they have no registered authenticator apps', function () {
        $user = User::factory()
            ->admin()
            ->withMfa()
            ->create();

        MfaFacade::pushChallengedUser($user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => '000000',
                ],
            ])
            ->call('authenticate')
            ->assertNoRedirect();

        assertGuest();

        Event::assertNotDispatched(AuthenticatorAppWasUsed::class);
    });
});

describe('validation', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();
    });

    it('requires a challenge code', function () {
        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => '',
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->provider->getId()}.code" => 'required',
            ])
            ->assertNoRedirect();

        assertGuest();
    });

    test('challenge code must be numeric', function () {
        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => Str::random(6),
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->provider->getId()}.code" => 'numeric',
            ])
            ->assertNoRedirect();

        assertGuest();
    });

    test('challenge code must be 6 digits', function () {
        MfaFacade::pushChallengedUser($this->user);

        $code = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => Str::limit(
                        $code,
                        limit: 5,
                        end: '',
                    ),
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->provider->getId()}.code" => 'digits',
            ])
            ->assertNoRedirect();

        assertGuest();
    });
});

describe('security', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();
    });

    it('can rate limit multi-factor challenge attempts per user', function () {
        MfaFacade::pushChallengedUser($this->user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        $invalidCode = ($this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret) === '000000')
            ? '111111'
            : '000000';

        $livewire = livewire($this->challengePage);

        foreach (range(1, 5) as $i) {
            $livewire
                ->fillForm([
                    $this->provider->getId() => [
                        'code' => $invalidCode,
                    ],
                ])
                ->call('authenticate')
                ->assertNoRedirect();
        }

        // The 6th attempt should be rate limited by user ID, even with a valid code.
        $validCode = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        $livewire
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $validCode,
                ],
            ])
            ->call('authenticate')
            ->assertNotified()
            ->assertNoRedirect();

        assertGuest();

        Event::assertNotDispatched(AuthenticatorAppWasUsed::class);

        // A different user should not be affected by the first user's rate limit.
        $secondUser = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();

        MfaFacade::pushChallengedUser($secondUser);
        app()->forgetInstance(MfaService::class);
        MfaFacade::clearResolvedInstance(MfaService::class);

        $validCode = $this->provider->getCurrentCode($secondUser->authenticatorApps->first()->secret);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $validCode,
                ],
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($secondUser);

        Event::assertDispatched(function (AuthenticatorAppWasUsed $event) use ($secondUser): bool {
            expect($event->user)->toBe($secondUser)
                ->and($event->authenticatorApp)->toBe($secondUser->authenticatorApps->first());

            return true;
        });
    });

    it('will not allow a TOTP code to be reused within the same time window', function () {
        MfaFacade::pushChallengedUser($this->user);
        Event::fake([AuthenticatorAppWasUsed::class]);

        $validCode = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        // First login with the TOTP code that should succeed.
        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $validCode,
                ],
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        Event::assertDispatchedTimes(AuthenticatorAppWasUsed::class, times: 1);

        assertAuthenticatedAs($this->user);

        auth()->logout();

        assertGuest();

        // Second login with the same TOTP code should fail (replay protection).
        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->fillForm([
                $this->provider->getId() => [
                    'code' => $validCode,
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->provider->getId()}.code",
            ])
            ->assertNoRedirect();

        assertGuest();

        Event::assertDispatchedTimes(AuthenticatorAppWasUsed::class, times: 1);
    });
});

describe('browser tests', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();
    });

    it('can authenticate with an authenticator app code', function () {
        $code = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/app/provider.challenge-form.code.label'))
            // ->assertNoAccessibilityIssues()
            ->type('input[autocomplete="one-time-code"]', $code)
            ->submit()
            ->assertUrlIs($this->panel->getUrl())
            ->assertSee('Dashboard')
            ->assertNoSmoke();

        expect($this->user->authenticatorApps->first()->fresh())->last_used_at->not->toBeNull();

        assertAuthenticatedAs($this->user);
    });

    test('dark mode can authenticate with an authenticator app code', function () {
        $code = $this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret);

        visit($this->panel->getLoginUrl())
            ->inDarkMode()
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/app/provider.challenge-form.code.label'))
            // ->assertNoAccessibilityIssues()
            ->type('input[autocomplete="one-time-code"]', $code)
            ->submit()
            ->assertUrlIs($this->panel->getUrl());

        expect($this->user->authenticatorApps->first()->fresh())->last_used_at->not->toBeNull();

        assertAuthenticatedAs($this->user);
    });

    it('will not authenticate with an invalid authenticator app code', function () {
        $invalidCode = ($this->provider->getCurrentCode($this->user->authenticatorApps->first()->secret) === '000000')
            ? '111111'
            : '000000';

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/app/provider.challenge-form.code.label'))
            ->type('input[autocomplete="one-time-code"]', $invalidCode)
            ->submit()
            ->assertRoute($this->panel->generateRouteName('auth.multi-factor-challenge'))
            ->assertSee(__('profile-filament::auth/multi-factor/app/provider.challenge-form.code.messages.invalid'))
            ->assertNoSmoke();
        // ->assertNoAccessibilityIssues()

        expect($this->user->authenticatorApps->first()->fresh())->last_used_at->toBeNull();

        assertGuest();
    });
});
