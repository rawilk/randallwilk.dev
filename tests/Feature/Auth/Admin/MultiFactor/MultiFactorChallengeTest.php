<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\Login;
use App\Filament\Admin\Pages\Auth\MultiFactorChallenge;
use App\Models\User;
use Rawilk\ProfileFilament\Auth\Multifactor\App\AppAuthenticationProvider;
use Rawilk\ProfileFilament\Auth\Multifactor\Enums\MfaSession;
use Rawilk\ProfileFilament\Auth\Multifactor\Events\MultiFactorAuthenticationChallengeWasPresented;
use Rawilk\ProfileFilament\Auth\Multifactor\Facades\Mfa as MfaFacade;
use Rawilk\ProfileFilament\Models\AuthenticatorApp;
use Rawilk\ProfileFilament\Models\WebauthnKey;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\be;
use function Pest\Laravel\freezeSecond;
use function Pest\Laravel\get;
use function Pest\Laravel\travelTo;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->loginPage = Login::class;
    $this->challengePage = MultiFactorChallenge::class;
    $this->challengeUrl = $this->panel->route('auth.multi-factor-challenge');
});

describe('authentication flow', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->hasRecoveryCodes()
            ->has(AuthenticatorApp::factory())
            ->create();
    });

    it('can render the challenge form after login credentials are successfully used', function () {
        Event::fake([MultiFactorAuthenticationChallengeWasPresented::class]);

        livewire($this->loginPage)
            ->fillForm([
                'email' => $this->user->email,
                'password' => 'secret',
                'remember' => true,
            ])
            ->call('authenticate')
            ->assertRedirect($this->challengeUrl);

        // Verify the challenge page renders.
        get($this->challengeUrl)
            ->assertOk()
            ->assertSeeLivewire($this->challengePage);

        expect(MfaSession::UserBeingAuthenticated->get())->toBe((string) $this->user->getAuthIdentifier())
            ->and(MfaSession::Remember->isTrue())->toBeTrue();

        assertGuest();

        Event::assertDispatched(function (MultiFactorAuthenticationChallengeWasPresented $event): bool {
            expect($event->user)->toBe($this->user);

            return true;
        });
    });

    it('will not redirect to the challenge if invalid login credentials are used', function () {
        livewire($this->loginPage)
            ->fillForm([
                'email' => $this->user->email,
                'password' => 'incorrect-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors()
            ->assertNoRedirect();

        assertGuest();

        expect(MfaSession::UserBeingAuthenticated->has())->toBeFalse();
    });

    it('will not redirect to the challenge if user does not have mfa enabled', function () {
        $user = adminUser();

        livewire($this->loginPage)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($user);

        expect(MfaSession::UserBeingAuthenticated->has())->toBeFalse();
    });
});

describe('redirects', function () {
    it('redirects if authenticated', function () {
        be(adminUser());

        get($this->challengeUrl)
            ->assertRedirect($this->panel->getUrl());
    });

    it('redirects if mfa session is not valid', function () {
        get($this->challengeUrl)
            ->assertRedirect($this->panel->getLoginUrl());
    });

    it('redirects if password confirmation has expired', function () {
        freezeSecond();

        MfaFacade::pushChallengedUser(adminUser());

        travelTo(now()->addMinutes(15)->addSecond());

        get($this->challengeUrl)
            ->assertRedirect($this->panel->getLoginUrl());
    });

    it('will not authenticate the user from the form after password confirmation has expired', function () {
        freezeSecond();

        $user = User::factory()
            ->admin()
            ->withMfa()
            ->has(AuthenticatorApp::factory())
            ->create();

        $provider = Rawilk\ProfileFilament\Facades\ProfileFilament::plugin()->getMultiFactorAuthenticationProvider(
            AppAuthenticationProvider::ID,
        );

        MfaFacade::pushChallengedUser($user);

        $component = livewire($this->challengePage);

        travelTo(now()->addMinutes(15)->addSecond());

        $code = $provider->getCurrentCode($user->authenticatorApps->first()->secret);

        $component
            ->fillForm([
                $provider->getId() => [
                    'code' => $code,
                ],
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getLoginUrl());

        assertGuest();
    });
});

describe('browser tests', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->hasRecoveryCodes()
            ->has(AuthenticatorApp::factory())
            ->has(WebauthnKey::factory(), 'securityKeys')
            ->create();
    });

    it('can redirect to the challenge form successfully', function () {
        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->click('button[type="submit"]')
            ->assertSee(__('profile-filament::auth/multi-factor/challenge/challenge.heading'))
            ->assertNoSmoke()
            ->assertNoAccessibilityIssues();
    });

    test('dark mode is accessible', function () {
        visit($this->panel->getLoginUrl())
            ->inDarkMode()
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->click('button[type="submit"]')
            ->assertNoAccessibilityIssues();
    });

    it('can select each alternative challenge provider', function () {
        $changeProviderLabel = __('profile-filament::auth/multi-factor/challenge/challenge.actions.change-provider.label');
        $appProviderLabel = __('profile-filament::auth/multi-factor/app/provider.challenge-form.actions.change-provider.label');
        $webauthnProviderLabel = __('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.actions.change-provider.label');
        $recoveryProviderLabel = __('profile-filament::auth/multi-factor/recovery/provider.challenge-form.actions.change-provider.label');

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->click('button[type="submit"]')
            ->assertSee(__('profile-filament::auth/multi-factor/challenge/challenge.heading'))
            ->click($changeProviderLabel)
            ->assertNoSmoke()
            ->assertSee(__('profile-filament::auth/multi-factor/challenge/challenge.form.provider.heading'))
            ->click($appProviderLabel)
            ->assertSee(__('profile-filament::auth/multi-factor/app/provider.challenge-form.code.label'))
            ->click($changeProviderLabel)
            ->assertNoSmoke()
            ->assertNoAccessibilityIssues()
            ->click($webauthnProviderLabel)
            ->assertSee(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.form.prompt.label'))
            ->click($changeProviderLabel)
            ->assertNoSmoke()
            ->assertNoAccessibilityIssues()
            ->click($recoveryProviderLabel)
            ->assertSee(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.code.label'))
            ->assertNoSmoke()
            ->assertNoAccessibilityIssues();
    });
});
