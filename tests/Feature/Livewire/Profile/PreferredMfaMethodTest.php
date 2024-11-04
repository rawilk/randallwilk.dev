<?php

declare(strict_types=1);

use App\Enums\UserSetting;
use App\Livewire\Profile\PreferredMfaMethod;
use App\Models\User;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\be;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()
        ->withMfa()
        ->hasAuthenticatorApps()
        ->hasWebauthnKeys()
        ->create();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    livewire(PreferredMfaMethod::class)
        ->assertOk();
});

it('shows the form when user has mfa enabled', function () {
    livewire(PreferredMfaMethod::class)
        ->assertSet('showForm', true)
        ->assertCount('availableMethods', 2);
});

it('shows the users current preferred mfa method', function (string $key) {
    $this->user->settings()->set(UserSetting::PreferredMfaMethod, $key);

    livewire(PreferredMfaMethod::class)
        ->assertFormSet([
            'selectedMethod' => $key,
        ]);
})->with([
    MfaChallengeMode::App->value,
    MfaChallengeMode::Webauthn->value,
]);

it('updates a users preferred mfa method', function (string $key) {
    livewire(PreferredMfaMethod::class)
        ->fillForm([
            'selectedMethod' => $key,
        ])
        ->call('store')
        ->assertOk();

    expect($this->user->settings()->get(UserSetting::PreferredMfaMethod))->toBe($key);
})->with([
    MfaChallengeMode::App->value,
    MfaChallengeMode::Webauthn->value,
]);

it('does not show the form if user has no available mfa methods', function () {
    actingAs(User::factory()->withMfa()->create());

    livewire(PreferredMfaMethod::class)
        ->assertSet('showForm', false);
});

it('only allows options that the user has enabled on their account', function () {
    $this->user->webauthnKeys()->delete();

    livewire(PreferredMfaMethod::class)
        ->assertCount('availableMethods', 1)
        ->fillForm([
            'selectedMethod' => MfaChallengeMode::Webauthn->value,
        ])
        ->call('store')
        ->assertHasFormErrors(['selectedMethod']);

    expect($this->user->settings()->get(UserSetting::PreferredMfaMethod))->not->toBe(MfaChallengeMode::Webauthn->value);
});
