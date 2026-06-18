<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Auth\Login;
use App\Filament\Admin\Pages\Auth\MultiFactorChallenge;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\URL;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Pest\Browser\Api\AwaitableWebpage;
use Pest\Browser\Playwright\InitScript;
use Rawilk\ProfileFilament\Auth\Multifactor\Facades\Mfa as MfaFacade;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Actions\FindSecurityKeyToAuthenticateAction;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Enums\WebauthnSession;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Events\SecurityKeyWasUsed;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Support\CredentialRecordConverter;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\WebauthnProvider;
use Rawilk\ProfileFilament\Models\WebauthnKey;
use Symfony\Component\Uid\Uuid;
use Tests\TestSupport\Actions\ConfigureWebauthnCeremonyForBrowserTestAction;
use Tests\TestSupport\Actions\FakeWebauthnFindSecurityKeyToAuthenticateAction;
use Tests\TestSupport\Browser\VirtualWebauthn;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\TrustPath\EmptyTrustPath;
use Webauthn\U2FPublicKey;

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

    $this->providerId = WebauthnProvider::ID;
    $this->provider = Rawilk\ProfileFilament\Facades\ProfileFilament::plugin()->getMultiFactorAuthenticationProvider(
        $this->providerId,
    );

    config()->set(
        'profile-filament.webauthn.actions.find_security_key_to_authenticate',
        FakeWebauthnFindSecurityKeyToAuthenticateAction::class,
    );

    FakeWebauthnFindSecurityKeyToAuthenticateAction::reset();

    freezeSecond();
});

describe('authentication flow', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->has(WebauthnKey::factory(), 'securityKeys')
            ->create();

        $this->securityKey = $this->user->securityKeys->first();
    });

    it('will authenticate the user after a valid security key challenge is used', function () {
        MfaFacade::pushChallengedUser($this->user);
        WebauthnSession::AuthenticationOptions->put('{"challenge":"test-challenge"}');
        FakeWebauthnFindSecurityKeyToAuthenticateAction::$securityKey = $this->securityKey;
        Event::fake([SecurityKeyWasUsed::class]);

        livewire($this->challengePage)
            ->callAction(
                TestAction::make('authenticateWebauthn')->schemaComponent($this->providerId, 'form'),
                arguments: [
                    'authenticationResponse' => FakeWebauthnFindSecurityKeyToAuthenticateAction::VALID_RESPONSE,
                ],
            )
            ->assertHasNoErrors()
            ->assertRedirect($this->panel->getUrl());

        expect($this->securityKey->fresh())->last_used_at->toBe(now())
            ->and(FakeWebauthnFindSecurityKeyToAuthenticateAction::$calls)->toHaveCount(1)
            ->and(FakeWebauthnFindSecurityKeyToAuthenticateAction::$calls[0]['requiresPasskey'])->toBeFalse()
            ->and(FakeWebauthnFindSecurityKeyToAuthenticateAction::$calls[0]['userBeingAuthenticated']->is($this->user))->toBeTrue();

        assertAuthenticatedAs($this->user);

        Event::assertDispatched(function (SecurityKeyWasUsed $event): bool {
            expect($event->user->is($this->user))->toBeTrue()
                ->and($event->webauthnKey->is($this->securityKey))->toBeTrue()
                ->and($event->request->input('webauthnResponse'))->toBe(FakeWebauthnFindSecurityKeyToAuthenticateAction::VALID_RESPONSE);

            return true;
        });
    });

    it('will not authenticate the user when the security key challenge is invalid', function () {
        MfaFacade::pushChallengedUser($this->user);
        WebauthnSession::AuthenticationOptions->put('{"challenge":"test-challenge"}');
        FakeWebauthnFindSecurityKeyToAuthenticateAction::$securityKey = $this->securityKey;
        Event::fake([SecurityKeyWasUsed::class]);

        livewire($this->challengePage)
            ->callAction(
                TestAction::make('authenticateWebauthn')->schemaComponent($this->providerId, 'form'),
                arguments: [
                    'authenticationResponse' => 'invalid-webauthn-response',
                ],
            )
            ->assertDispatched('webauthnAuthenticationFailed')
            ->assertNoRedirect();

        expect($this->securityKey->fresh())->last_used_at->toBeNull()
            ->and(FakeWebauthnFindSecurityKeyToAuthenticateAction::$calls)->toHaveCount(1);

        assertGuest();

        Event::assertNotDispatched(SecurityKeyWasUsed::class);
    });

    it("will not authenticate a user with another user's security key", function () {
        $otherUser = User::factory()
            ->admin()
            ->withMfa()
            ->has(WebauthnKey::factory(), 'securityKeys')
            ->create();

        $otherSecurityKey = $otherUser->securityKeys->first();

        MfaFacade::pushChallengedUser($this->user);
        WebauthnSession::AuthenticationOptions->put('{"challenge":"test-challenge"}');
        FakeWebauthnFindSecurityKeyToAuthenticateAction::$securityKey = $otherSecurityKey;
        Event::fake([SecurityKeyWasUsed::class]);

        livewire($this->challengePage)
            ->callAction(
                TestAction::make('authenticateWebauthn')->schemaComponent($this->providerId, 'form'),
                arguments: [
                    'authenticationResponse' => FakeWebauthnFindSecurityKeyToAuthenticateAction::VALID_RESPONSE,
                ],
            )
            ->assertDispatched('webauthnAuthenticationFailed')
            ->assertNoRedirect();

        expect($this->securityKey->fresh())->last_used_at->toBeNull()
            ->and($otherSecurityKey->fresh())->last_used_at->toBeNull();

        assertGuest();

        Event::assertNotDispatched(SecurityKeyWasUsed::class);
    });

    it('will not authenticate a user with webauthn when they have no registered security keys', function () {
        $user = User::factory()
            ->admin()
            ->withMfa()
            ->create();

        MfaFacade::pushChallengedUser($user);
        WebauthnSession::AuthenticationOptions->put('{"challenge":"test-challenge"}');
        Event::fake([SecurityKeyWasUsed::class]);

        livewire($this->challengePage)
            ->call('authenticate')
            ->assertNoRedirect();

        assertGuest();

        Event::assertNotDispatched(SecurityKeyWasUsed::class);
    });
});

describe('validation', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->has(WebauthnKey::factory(), 'securityKeys')
            ->create();
    });

    it('will not authenticate when authenticate is called without a verified webauthn challenge', function () {
        MfaFacade::pushChallengedUser($this->user);
        Event::fake([SecurityKeyWasUsed::class]);

        livewire($this->challengePage)
            ->call('authenticate')
            ->assertHasErrors(['multiFactorError'])
            ->assertNoRedirect();

        assertGuest();

        Event::assertNotDispatched(SecurityKeyWasUsed::class);
    });

    it('will not authenticate when the verified webauthn challenge does not match the session challenge', function () {
        MfaFacade::pushChallengedUser($this->user);
        WebauthnSession::ChallengeAssertion->put('expected-challenge');
        Event::fake([SecurityKeyWasUsed::class]);

        livewire($this->challengePage)
            ->fillForm([
                $this->providerId => [
                    '_webauthn_challenge' => Crypt::encryptString('different-challenge'),
                ],
            ])
            ->call('authenticate')
            ->assertHasErrors(['multiFactorError'])
            ->assertNoRedirect();

        assertGuest();

        Event::assertNotDispatched(SecurityKeyWasUsed::class);
    });
});

describe('browser tests', function () {
    beforeEach(function () {
        $this->user = User::factory()
            ->admin()
            ->withMfa()
            ->create();
    });

    it('renders an accessible webauthn provider form without javascript errors', function () {
        $this->user->securityKeys()->save(WebauthnKey::factory()->make());

        visit($this->panel->getLoginUrl())
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.form.prompt.label'))
            ->assertSee(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.actions.authenticate.label'))
            ->assertNoAccessibilityIssues()
            ->assertNoSmoke();
    });

    test('dark mode renders an accessible webauthn provider form without javascript errors', function () {
        $this->user->securityKeys()->save(WebauthnKey::factory()->make());

        visit($this->panel->getLoginUrl())
            ->inDarkMode()
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.form.prompt.label'))
            ->assertSee(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.actions.authenticate.label'))
            ->assertNoAccessibilityIssues()
            ->assertNoSmoke();
    });

    it('can authenticate with a registered security key without faking the lookup action', function () {
        config()->set('profile-filament.webauthn.actions.find_security_key_to_authenticate', FindSecurityKeyToAuthenticateAction::class);
        config()->set('profile-filament.webauthn.relying_party.id', 'localhost');

        $loginUrl = parse_url($this->panel->getLoginUrl(), PHP_URL_PATH);

        $bootstrapPage = visit($loginUrl);
        $origin = str_replace('127.0.0.1', 'localhost', $bootstrapPage->script('() => window.location.origin'));

        config()->set('app.url', $origin);
        URL::useOrigin($origin);
        config()->set('profile-filament.webauthn.actions.configure_ceremony_step_manager_factory', ConfigureWebauthnCeremonyForBrowserTestAction::class);

        $context = $bootstrapPage->page()->context()->browser()->newContext([
            'locale' => 'en-US',
            'timezoneId' => 'UTC',
        ]);

        $credential = VirtualWebauthn::create($context, 'localhost', [
            'userHandle' => Base64UrlSafe::encodeUnpadded((string) $this->user->getAuthIdentifier()),
        ]);

        $publicKeyDetails = openssl_pkey_get_details(openssl_pkey_get_public(
            "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split(base64_encode(Base64UrlSafe::decodeNoPadding($credential['publicKey'])), 64, "\n")
            . "-----END PUBLIC KEY-----\n",
        ));

        $securityKey = $this->user->securityKeys()->create([
            'name' => 'Browser test key',
            'attachment_type' => 'platform',
            'is_passkey' => false,
            'data' => CredentialRecordConverter::toPublicKeyCredentialSource(PublicKeyCredentialSource::create(
                Base64UrlSafe::decodeNoPadding($credential['id']),
                PublicKeyCredentialDescriptor::CREDENTIAL_TYPE_PUBLIC_KEY,
                [],
                'none',
                EmptyTrustPath::create(),
                Uuid::fromString('00000000-0000-0000-0000-000000000000'),
                U2FPublicKey::convertToCoseKey("\x04" . $publicKeyDetails['ec']['x'] . $publicKeyDetails['ec']['y']),
                Base64UrlSafe::decodeNoPadding($credential['userHandle']),
                0,
            )),
        ]);

        VirtualWebauthn::install($context);

        $context->addInitScript(InitScript::get());

        $page = new AwaitableWebpage(
            $context->newPage()->goto("{$origin}{$loginUrl}"),
            "{$origin}{$loginUrl}",
        );

        $page
            ->type('input[type="email"]', $this->user->email)
            ->type('input[type="password"]', 'secret')
            ->submit()
            ->assertSee(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.form.prompt.label'))
            ->press(__('profile-filament::auth/multi-factor/webauthn/provider.challenge-form.actions.authenticate.label'))
            ->assertUrlIs("{$origin}/admin")
            ->assertSee('Dashboard')
            ->assertNoSmoke();

        expect($securityKey->fresh()->last_used_at)->not->toBeNull();
    });
});
