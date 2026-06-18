<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\Login;
use App\Filament\Admin\Pages\Auth\MultiFactorChallenge;
use App\Models\User;
use App\Notifications\Auth\RecoveryCodeWasUsedNotification;
use Filament\Actions\Testing\TestAction;
use Rawilk\ProfileFilament\Auth\Multifactor\Facades\Mfa as MfaFacade;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Events\RecoveryCodeWasUsed;
use Rawilk\ProfileFilament\Auth\Multifactor\Services\Mfa as MfaService;
use Rawilk\ProfileFilament\Models\AuthenticatorApp;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->loginPage = Login::class;
    $this->challengePage = MultiFactorChallenge::class;
    $this->challengeUrl = $this->panel->route('auth.multi-factor-challenge');

    $this->providerId = '_recovery_';
    $this->provider = Rawilk\ProfileFilament\Facades\ProfileFilament::plugin()->getMultiFactorRecoveryProvider();

    $this->user = User::factory()
        ->admin()
        ->withMfa()
        ->hasRecoveryCodes()
        ->has(AuthenticatorApp::factory())
        ->create();
});

describe('authentication flow', function () {
    it('will make the recovery code field visible when the user requests it', function () {
        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->assertFormFieldExists(
                "{$this->providerId}.recoveryCode",
                checkFieldUsing: fn (Rawilk\FilamentPasswordInput\Password $field) => $field->getContainer()->isHidden(),
            )
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->assertFormFieldExists(
                "{$this->providerId}.recoveryCode",
                checkFieldUsing: fn (Rawilk\FilamentPasswordInput\Password $field) => $field->getContainer()->isVisible(),
            );
    });

    it('will authenticate the user after a valid recovery code is used', function () {
        Event::fake([RecoveryCodeWasUsed::class]);

        MfaFacade::pushChallengedUser($this->user);

        expect($this->user->getAuthenticationRecoveryCodes())->toHaveCount(4);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => 'one',
                ],
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($this->user);

        expect($this->user->fresh()->getAuthenticationRecoveryCodes())->toHaveCount(3);

        Event::assertDispatched(function (RecoveryCodeWasUsed $event): bool {
            expect($event->user)->toBe($this->user);

            return true;
        });
    });

    it('sends a notification to the user when a recovery code is used', function () {
        Notification::fake();

        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => 'two',
                ],
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($this->user);

        Notification::assertSentTo($this->user, function (RecoveryCodeWasUsedNotification $notification): bool {
            $actualValue = (fn () => $this->codesRemaining)->call($notification);

            expect($actualValue)->toBe(3);

            return true;
        });
    });

    it('sends a notification showing zero codes remaining when the final recovery code is used', function () {
        Notification::fake();

        $this->provider->saveRecoveryCodes(
            user: $this->user,
            codes: ['final-code'],
        );

        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => 'final-code',
                ],
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($this->user);

        expect($this->user->fresh()->getAuthenticationRecoveryCodes())->toHaveCount(0);

        Notification::assertSentTo($this->user, function (RecoveryCodeWasUsedNotification $notification): bool {
            $actualValue = (fn () => $this->codesRemaining)->call($notification);

            expect($actualValue)->toBe(0);

            return true;
        });
    });
});

describe('validation', function () {
    it('requires a recovery code', function () {
        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => '',
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->providerId}.recoveryCode" => ['required'],
            ]);
    });

    it('will not authenticate the user when an invalid recovery code is used', function () {
        Event::fake([RecoveryCodeWasUsed::class]);

        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => 'invalid-recovery-code',
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->providerId}.recoveryCode",
            ]);

        assertGuest();

        expect($this->user->fresh()->getAuthenticationRecoveryCodes())->toHaveCount(4);

        Event::assertNotDispatched(RecoveryCodeWasUsed::class);
    });

    it('will not replay recovery codes', function () {
        Event::fake([RecoveryCodeWasUsed::class]);

        MfaFacade::pushChallengedUser($this->user);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => 'one',
                ],
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($this->user);

        Event::assertDispatchedTimes(RecoveryCodeWasUsed::class, times: 1);

        auth()->logout();

        assertGuest();

        MfaFacade::pushChallengedUser($this->user);
        app()->forgetInstance(MfaService::class);
        MfaFacade::clearResolvedInstance(MfaService::class);

        livewire($this->challengePage)
            ->callAction(TestAction::make('changeProvider')->schemaComponent('change-provider-actions', 'content'))
            ->callAction(TestAction::make("changeProvider.{$this->providerId}")->schemaComponent(schema: 'form'))
            ->fillForm([
                $this->providerId => [
                    'recoveryCode' => 'one',
                ],
            ])
            ->call('authenticate')
            ->assertHasFormErrors([
                "{$this->providerId}.recoveryCode",
            ])
            ->assertNoRedirect();

        assertGuest();

        Event::assertDispatchedTimes(RecoveryCodeWasUsed::class, times: 1);
    });

    it('will not preserve a recovery code when a different code is used concurrently', function () {
        Notification::fake();

        $this->provider->saveRecoveryCodes(
            user: $this->user,
            codes: $codes = $this->provider->generateRecoveryCodes(),
        );

        $initialCount = count($this->user->refresh()->getAuthenticationRecoveryCodes());

        $userInstanceA = User::find($this->user->getKey());
        $userInstanceB = User::find($this->user->getKey());

        expect($this->provider->verifyRecoveryCode($codes[0], $userInstanceA))->toBeTrue()
            ->and($this->provider->verifyRecoveryCode($codes[1], $userInstanceB))->toBeTrue();

        $this->user->refresh();

        expect($this->user->getAuthenticationRecoveryCodes())->toHaveCount($initialCount - 2)
            ->and($this->provider->verifyRecoverycode($codes[0], $this->user->fresh()))->toBeFalse();
    });

    it('will not allow the same recovery code to authenticate to concurrent requests', function () {
        Notification::fake();

        $this->provider->saveRecoveryCodes(
            user: $this->user,
            codes: $codes = $this->provider->generateRecoveryCodes(),
        );

        $userInstanceA = User::find($this->user->getKey());
        $userInstanceB = User::find($this->user->getKey());

        $code = $codes[0];

        expect($this->provider->verifyRecoveryCode($code, $userInstanceA))->toBeTrue()
            ->and($this->provider->verifyRecoveryCode($code, $userInstanceB))->toBeFalse();
    });
});

describe('browser tests', function () {
    it('can authenticate with a recovery code', function () {
        Notification::fake();

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/app/provider.challenge-form.code.label'))
            ->click(__('profile-filament::auth/multi-factor/challenge/challenge.actions.change-provider.label'))
            ->assertSee(__('profile-filament::auth/multi-factor/challenge/challenge.form.provider.heading'))
            ->click(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.actions.change-provider.label'))
            ->assertSee(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.code.label'))
            ->click('input[type="password"]')
            ->assertNoAccessibilityIssues()
            ->assertNoSmoke()
            ->type('input[type="password"]', 'one')
            ->submit()
            ->assertUrlIs($this->panel->getUrl())
            ->assertSee('Dashboard')
            ->assertNoSmoke();

        expect($this->user->fresh()->getAuthenticationRecoveryCodes())->toHaveCount(3);

        assertAuthenticatedAs($this->user);
    });

    test('dark mode can authenticate with a recovery code', function () {
        Notification::fake();

        visit($this->panel->getLoginUrl())
            ->inDarkMode()
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->click(__('profile-filament::auth/multi-factor/challenge/challenge.actions.change-provider.label'))
            ->click(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.actions.change-provider.label'))
            ->assertSee(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.code.label'))
            ->click('input[type="password"]')
            ->assertNoAccessibilityIssues()
            ->type('input[type="password"]', 'two')
            ->submit()
            ->assertUrlIs($this->panel->getUrl());

        expect($this->user->fresh()->getAuthenticationRecoveryCodes())->toHaveCount(3);

        assertAuthenticatedAs($this->user);
    });

    it('will not authenticate with an invalid recovery code', function () {
        Event::fake([RecoveryCodeWasUsed::class]);

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->click(__('profile-filament::auth/multi-factor/challenge/challenge.actions.change-provider.label'))
            ->click(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.actions.change-provider.label'))
            ->assertSee(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.code.label'))
            ->type('input[type="password"]', 'invalid-recovery-code')
            ->submit()
            ->assertRoute($this->panel->generateRouteName('auth.multi-factor-challenge'))
            ->assertSee(__('profile-filament::auth/multi-factor/recovery/provider.challenge-form.code.messages.invalid'))
            ->assertNoSmoke()
            ->click('input[type="password"]')
            ->assertNoAccessibilityIssues();

        expect($this->user->fresh()->getAuthenticationRecoveryCodes())->toHaveCount(4);

        assertGuest();

        Event::assertNotDispatched(RecoveryCodeWasUsed::class);
    });
});
