<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rawilk\ProfileFilament\Models as ProfileFilamentModels;
use Rawilk\Settings\Models\Setting;

readonly class MorphMapConfig
{
    public static function configure(): void
    {
        Relation::enforceMorphMap([
            'authenticator_app' => ProfileFilamentModels\AuthenticatorApp::class,
            'pending_user_email' => ProfileFilamentModels\PendingUserEmail::class,
            'repository' => Repository::class,
            'setting' => Setting::class,
            'user' => User::class,
            'webauthn_key' => ProfileFilamentModels\WebauthnKey::class,
        ]);
    }
}
