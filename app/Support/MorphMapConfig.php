<?php

declare(strict_types=1);

namespace App\Support;

use App\Models;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rawilk\ProfileFilament\Models as ProfileFilamentModels;
use Rawilk\Settings\Models\Setting;

readonly class MorphMapConfig
{
    public static function configure(): void
    {
        Relation::enforceMorphMap([
            'authenticator_app' => ProfileFilamentModels\AuthenticatorApp::class,
            'old_user_email' => ProfileFilamentModels\OldUserEmail::class,
            'pending_user_email' => ProfileFilamentModels\PendingUserEmail::class,
            'repository' => Models\Repository::class,
            'setting' => Setting::class,
            'user' => Models\User::class,
            'webauthn_key' => Models\WebauthnKey::class,
        ]);
    }
}
