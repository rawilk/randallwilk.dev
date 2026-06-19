<?php

declare(strict_types=1);

namespace Tests\TestSupport\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Actions\FindSecurityKeyToAuthenticateAction;
use Rawilk\ProfileFilament\Models\WebauthnKey;

final class FakeWebauthnFindSecurityKeyToAuthenticateAction extends FindSecurityKeyToAuthenticateAction
{
    public const string VALID_RESPONSE = 'valid-webauthn-response';

    public static ?WebauthnKey $securityKey = null;

    public static array $calls = [];

    public function __invoke(
        string $publicKeyCredentialJson,
        string $securityKeyOptionsJson,
        bool $requiresPasskey = false,
        ?Authenticatable $userBeingAuthenticated = null,
    ): ?WebauthnKey {
        self::$calls[] = [
            'publicKeyCredentialJson' => $publicKeyCredentialJson,
            'securityKeyOptionsJson' => $securityKeyOptionsJson,
            'requiresPasskey' => $requiresPasskey,
            'userBeingAuthenticated' => $userBeingAuthenticated,
        ];

        if ($publicKeyCredentialJson !== self::VALID_RESPONSE) {
            return null;
        }

        if (! self::$securityKey) {
            return null;
        }

        if ($userBeingAuthenticated && ! self::$securityKey->user()->is($userBeingAuthenticated)) {
            return null;
        }

        self::$securityKey->forceFill([
            'last_used_at' => now(),
        ])->save();

        return self::$securityKey->fresh();
    }

    public static function reset(): void
    {
        self::$securityKey = null;
        self::$calls = [];
    }
}
