<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\Login;
use App\Models\User;
use Illuminate\Auth\Events\Login as LoginEvent;
use Rawilk\ProfileFilament\Enums\Session\MfaSession;
use Rawilk\ProfileFilament\Events\TwoFactorAuthenticationChallenged;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);
});

it('renders', function () {
    get($this->panel->getLoginUrl())
        ->assertSuccessful()
        ->assertSeeLivewire(Login::class);
});

test('authenticated users are redirected', function () {
    actingAs(adminUser())
        ->get($this->panel->getLoginUrl())
        ->assertRedirect($this->panel->getUrl());
});

test('a user can login', function () {
    $user = adminUser(['password' => 'secret']);

    Event::fake();

    livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'secret',
        ])
        ->call('authenticate')
        ->assertSuccessful()
        ->assertRedirect($this->panel->getUrl());

    $this->assertAuthenticatedAs($user);

    Event::assertDispatched(LoginEvent::class, function (LoginEvent $event) use ($user) {
        expect($event->user)->toBe($user);

        return true;
    });
});

test('email is required', function () {
    livewire(Login::class)
        ->fillForm([
            'email' => '',
            'password' => 'secret',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email' => ['required']]);
});

test('password is required', function () {
    livewire(Login::class)
        ->fillForm([
            'email' => 'email@example.com',
            'password' => '',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['password' => 'required']);
});

test('a correct password is required', function () {
    $user = adminUser(['password' => 'secret']);

    livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'invalid',
        ])
        ->call('authenticate')
        ->assertHasFormErrors([
            'email' => [__('auth.failed')],
        ]);

    $this->assertGuest();
});

test('login is rate limited', function () {
    $user = adminUser(['password' => 'secret']);

    $component = livewire(Login::class);

    for ($i = 0; $i < 5; $i++) {
        $component
            ->fillForm([
                'email' => $user->email,
                'password' => 'invalid',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email'])
            ->assertNotNotified();
    }

    $component
        ->fillForm([
            'email' => $user->email,
            'password' => 'invalid',
        ])
        ->call('authenticate')
        ->assertNotified();
});

test('login links are shown in test environments', function (string $environment) {
    $this->app->detectEnvironment(fn () => $environment);

    get($this->panel->getLoginUrl())
        ->assertSeeHtml('data-test="dev-logins"');
})->with(['local', 'staging']);

test('login links are not shown in production', function () {
    $this->app->detectEnvironment(fn () => 'production');

    get($this->panel->getLoginUrl())
        ->assertDontSeeHtml('data-test="dev-logins"');
});

it('redirects to an mfa challenge if the user has mfa enabled', function () {
    $user = User::factory()->admin()->withMfa()->create(['password' => 'secret']);

    Event::fake();

    livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'secret',
        ])
        ->call('authenticate')
        ->assertRedirect(route($this->panel->generateRouteName('auth.mfa.challenge')));

    $this->assertGuest();

    expect(session()->get(MfaSession::User->value))->toBe($user->getKey());

    Event::assertDispatched(TwoFactorAuthenticationChallenged::class, function (TwoFactorAuthenticationChallenged $event) use ($user) {
        expect($event->user)->toBe($user);

        return true;
    });
});
