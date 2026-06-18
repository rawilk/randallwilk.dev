<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\Login;
use App\Models\User;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->page = Login::class;
});

it('renders', function () {
    get($this->panel->getLoginUrl())
        ->assertSuccessful()
        ->assertSeeLivewire($this->page);
});

test('authenticated users are redirected', function () {
    actingAs(adminUser())
        ->get($this->panel->getLoginUrl())
        ->assertRedirect($this->panel->getUrl());
});

describe('authentication', function () {
    it('can authenticate', function () {
        assertGuest();

        $user = adminUser();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getUrl());

        assertAuthenticatedAs($user);
    });

    it('rotates the session ID after a successful authentication to prevent session fixation', function () {
        $user = adminUser();

        $sessionIdBefore = session()->getId();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getUrl());

        expect(session()->getId())->not->toBe($sessionIdBefore);
    });

    it('rotates the CSRF token after a successful authentication', function () {
        $user = adminUser();

        session()->start();
        $csrfTokenBefore = session()->token();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertRedirect($this->panel->getUrl());

        expect(session()->token())->not->toBe($csrfTokenBefore);
    });

    it('does not rotate the CSRF token when authentication fails (control)', function () {
        $user = adminUser();

        session()->start();
        $csrfTokenBefore = session()->token();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        expect(session()->token())->toBe($csrfTokenBefore);
    });

    it('can authenticate and redirect user to their intended URL', function () {
        session()->put('url.intended', $intendedUrl = Str::random());

        $user = adminUser();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertRedirect($intendedUrl);
    });
});

describe('authentication failures', function () {
    it('requires the correct credentials', function () {
        Event::fake([Failed::class]);

        $user = adminUser();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        assertGuest();

        Event::assertDispatched(function (Failed $event) use ($user) {
            expect($event->guard)->toBe('web')
                ->and($event->user)->toBe($user)
                ->and($event->credentials)->toEqualCanonicalizing([
                    'email' => $user->email,
                    'password' => 'incorrect-password',
                ]);

            return true;
        });
    });

    it('fires the `Attempting` event when authentication fails because the email is unknown', function () {
        Event::fake([Attempting::class]);

        livewire($this->page)
            ->fillForm([
                'email' => 'not-exists@example.com',
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        Event::assertDispatched(function (Attempting $event): bool {
            expect($event->guard)->toBe('web')
                ->and($event->credentials)->toEqualCanonicalizing([
                    'email' => 'not-exists@example.com',
                    'password' => 'secret',
                ]);

            return true;
        });
    });

    it('fires the `Attempting` event when authentication fails because the password is wrong', function () {
        Event::fake([Attempting::class]);

        $user = adminUser();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        Event::assertDispatched(function (Attempting $event) use ($user): bool {
            expect($event->guard)->toBe('web')
                ->and($event->credentials)->toEqualCanonicalizing([
                    'email' => $user->email,
                    'password' => 'incorrect-password',
                ]);

            return true;
        });
    });

    it('cannot authenticate on unauthorized panel (i.e., app users cannot login to admin panel)', function () {
        Event::fake([Failed::class]);

        // Non-admin users cannot authenticate to the admin panel.
        $user = User::factory()->create();

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        assertGuest();

        Event::assertDispatched(function (Failed $event) use ($user): bool {
            expect($event->guard)->toBe('web')
                ->and($event->user)->toBe($user)
                ->and($event->credentials)->toEqualCanonicalizing([
                    'email' => $user->email,
                    'password' => 'secret',
                ]);

            return true;
        });
    });
});

describe('validation', function () {
    it('requires an email', function () {
        livewire($this->page)
            ->fillForm([
                'email' => '',
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email' => ['required']]);
    });

    it('requires a valid email', function () {
        livewire($this->page)
            ->fillForm([
                'email' => 'invalid-email',
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email' => ['email']]);
    });

    it('requires a password', function () {
        livewire($this->page)
            ->fillForm([
                'email' => 'email@gmail.com',
                'password' => '',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['password' => ['required']]);
    });
});

describe('rate limiting', function () {
    it('can throttle authentication attempts', function () {
        assertGuest();

        $user = adminUser();

        foreach (range(1, 5) as $i) {
            livewire($this->page)
                ->fillForm([
                    'email' => $user->email,
                    'password' => 'secret',
                ])
                ->call('authenticate');

            assertAuthenticated();

            auth()->logout();
        }

        livewire($this->page)
            ->fillForm([
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->call('authenticate')
            ->assertNotified();

        assertGuest();
    });
});

describe('login links', function () {
    it('shows them in test environments', function (string $environment) {
        app()->detectEnvironment(fn () => $environment);

        get($this->panel->getLoginUrl())
            ->assertSeeHtml('data-test="dev-logins"');
    })->with(['local', 'staging']);

    it('hides them in production', function () {
        putAppInProduction();

        get($this->panel->getLoginUrl())
            ->assertDontSeeHtml('data-test="dev-logins"');
    });
});

describe('browser testing', function () {
    it('can fill the form, authenticate, and redirect to the admin panel', function () {
        $user = adminUser();

        visit($this->panel->getLoginUrl())
            ->assertSee(__('pages/auth/login.heading'))
            ->assertNoSmoke()
            ->assertNoAccessibilityIssues()
            ->type('input[type="email"]', $user->email)
            ->type('input[type="password"]', 'secret')
            ->check('input[type="checkbox"]')
            ->click('button[type="submit"]')
            ->assertUrlIs($this->panel->getUrl())
            ->assertSee('Dashboard')
            ->assertNoSmoke();

        assertAuthenticatedAs($user);
    });

    test('dark mode is accessible', function () {
        visit($this->panel->getLoginUrl())
            ->inDarkMode()
            ->assertNoAccessibilityIssues();
    });

    it('shows authentication errors in the ui', function () {
        $user = adminUser();

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $user->email)
            ->type('input[type="password"]', 'incorrect-password')
            ->click('button[type="submit"]')
            ->assertUrlIs($this->panel->getLoginUrl())
            ->assertNoAccessibilityIssues()
            ->assertSee(__('auth.failed'));
    });
});
