<?php

declare(strict_types=1);

use App\Dto\Auth\GitHubLoginBag;
use App\Enums\SessionAlert;
use App\Models\User;
use App\Notifications\Auth\ConnectedAccounts\GitHubConnectedNotification;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery\MockInterface;
use Rawilk\ProfileFilament\Enums\Session\MfaSession;
use Rawilk\ProfileFilament\Events\TwoFactorAuthenticationChallenged;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

use function Pest\Laravel\be;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');

    filament()->setCurrentPanel($this->panel);

    $this->freezeSecond();
});

it('redirects to the GitHub url', function () {
    $url = URL::temporarySignedRoute(
        'login.github',
        now()->addMinute(),
        [
            'p' => $this->panel->getId(),
        ]
    );

    $driver = $this->mock(GithubProvider::class);
    $driver->shouldReceive('redirect')
        ->andReturn(new RedirectResponse('https://github.com'));

    Socialite::shouldReceive('driver')->andReturn($driver);

    get($url)
        ->assertRedirect('https://github.com');

    expect(session()->get('panel'))->toBe($this->panel->getId());
});

describe('login', function () {
    beforeEach(function () {
        session()->put('panel', $this->panel->getId());

        $this->user = User::factory()->admin()->create([
            'github_id' => 123456,
        ]);
    });

    it('logs a user in', function () {
        $socialiteUser = $this->mock(SocialiteUser::class);

        $socialiteUser->shouldReceive('getId')->andReturn(123456);
        $socialiteUser->shouldReceive('getNickname')->andReturn('example-user');
        $socialiteUser->shouldReceive('getAvatar')->andReturn($avatarUrl = 'https://en.gravatar.com/userimage');

        $this->app->bind(
            GitHubLoginBag::class,
            fn () => mockGitHubLoginBag(
                user: $this->user,
                socialiteUser: $socialiteUser,
                panelId: $this->panel->getId(),
                redirect: '/admin',
                isLoginRequest: true,
            ),
        );

        get(route('login.github.callback'))
            ->assertOk()
            ->assertSee("type: 'AUTH_COMPLETE'", escape: false)
            ->assertSee('redirectUrl: ' . Js::from('/admin'), escape: false);

        $this->assertAuthenticatedAs($this->user);

        expect($this->user->refresh())
            ->github_username->toBe('example-user')
            ->avatar_path->toBe($avatarUrl)
            ->and(session()->get('github.login'))->toBeTrue();
    });

    it('redirects mfa users to the mfa challenge instead of logging them in', function () {
        Event::fake();

        $this->user->update(['two_factor_enabled' => true]);

        $socialiteUser = $this->mock(SocialiteUser::class);

        $socialiteUser->shouldReceive('getId')->andReturn(123456);
        $socialiteUser->shouldReceive('getNickname')->andReturn('example-user');
        $socialiteUser->shouldReceive('getAvatar')->andReturn($avatarUrl = 'https://en.gravatar.com/userimage');

        $this->app->bind(
            GitHubLoginBag::class,
            fn () => mockGitHubLoginBag(
                user: $this->user,
                socialiteUser: $socialiteUser,
                panelId: $this->panel->getId(),
                redirect: route($this->panel->generateRouteName('auth.mfa.challenge')),
                isLoginRequest: true,
            ),
        );

        get(route('login.github.callback'))
            ->assertOk();

        $this->assertGuest();

        expect(session()->get('github.login'))->toBeTrue()
            ->and(session()->get(MfaSession::User->value))->toBe($this->user->getKey());

        Event::assertDispatched(TwoFactorAuthenticationChallenged::class);
    });

    it('prevents user registration', function () {
        $socialiteUser = $this->mock(SocialiteUser::class);

        $socialiteUser->shouldReceive('getId')->andReturn(654321);

        $this->app->bind(
            GitHubLoginBag::class,
            fn () => mockGitHubLoginBag(
                user: $this->user,
                socialiteUser: $socialiteUser,
                panelId: $this->panel->getId(),
                redirect: '/admin',
                isLoginRequest: true,
            ),
        );

        get(route('login.github.callback'))
            ->assertOk()
            // Assert our error message is being stored in the session
            ->assertSee('socialite.message')
            ->assertSee(Js::from([
                'type' => SessionAlert::Error->value,
                'message' => __('auth.alerts.registration_not_allowed'),
            ]), escape: false);

        $this->assertGuest();
    });
});

describe('link account', function () {
    beforeEach(function () {
        $this->user = User::factory()->admin()->create();

        be($this->user);

        session()->put('auth-user-id', $this->user->getKey());
        session()->put('panel', $this->panel->getId());
    });

    it('can link a GitHub account to a user', function () {
        Notification::fake();

        $socialiteUser = $this->mock(SocialiteUser::class);

        $socialiteUser->shouldReceive('getId')->andReturn($githubId = 123456);
        $socialiteUser->shouldReceive('getNickname')->andReturn('example-user');
        $socialiteUser->shouldReceive('getAvatar')->andReturn($avatarUrl = 'https://en.gravatar.com/userimage');

        $this->app->bind(
            GitHubLoginBag::class,
            fn () => mockGitHubLoginBag(
                user: $this->user,
                socialiteUser: $socialiteUser,
                panelId: $this->panel->getId(),
            ),
        );

        get(route('login.github.callback'))
            ->assertRedirect(ProfileInfo::getUrl(panel: $this->panel->getId()));

        expect($this->user->refresh())
            ->github_id->toBe($githubId)
            ->github_username->toBe('example-user')
            ->avatar_path->toBe($avatarUrl)
            ->and(session()->has(SessionAlert::Success->value))->toBeTrue()
            ->and(session()->missing('auth-user-id'))->toBeTrue();

        Notification::assertSentTo($this->user, GitHubConnectedNotification::class);
    });

    it('prevents two users from linking to the same account', function () {
        User::factory()->create([
            'github_id' => 123456,
        ]);

        Notification::fake();

        $socialiteUser = $this->mock(SocialiteUser::class);

        $socialiteUser->shouldReceive('getId')->andReturn(123456);

        $mock = mockGitHubLoginBag(
            user: $this->user,
            socialiteUser: $socialiteUser,
            panelId: $this->panel->getId(),
        );

        $this->app->bind(
            GitHubLoginBag::class,
            fn () => $mock,
        );

        $mock->shouldReceive('errorRedirectUrl')->andReturn(ProfileInfo::getUrl(panel: $this->panel->getId()));

        get(route('login.github.callback'))
            ->assertRedirect(ProfileInfo::getUrl(panel: $this->panel->getId()));

        expect(session()->get(SessionAlert::Error->value))->toBe(__('auth.socialite.alerts.already_linked'))
            ->and($this->user->refresh())
            ->github_id->toBeNull()
            ->github_username->toBeNull();

        Notification::assertNotSentTo($this->user, GitHubConnectedNotification::class);
    });
});

function mockGitHubLoginBag(
    User $user,
    $socialiteUser,
    string $panelId,
    ?string $redirect = null,
    bool $isLoginRequest = false,
): MockInterface {
    $redirect ??= ProfileInfo::getUrl(panel: $panelId);

    $mock = Mockery::mock(GitHubLoginBag::class);

    $mock->shouldReceive('gitHubUser')->andReturn($socialiteUser);
    $mock->shouldReceive('setUser')->withArgs(
        fn ($other) => $user->is($other),
    );

    $mock->shouldReceive('user')->andReturn($user);
    $mock->shouldReceive('isLoginRequest')->andReturn($isLoginRequest);
    $mock->shouldReceive('shouldLogin')->andReturn($isLoginRequest);
    $mock->shouldReceive('panelId')->andReturn($panelId);
    $mock->shouldReceive('redirect')->andReturn($redirect);

    return $mock;
}
