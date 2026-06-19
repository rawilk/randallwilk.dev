<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasCaseInsensitiveEmail;
use App\Models\Concerns\UsesHumanKeys;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rawilk\LaravelCasters\Contracts\HasSingleNameColumn;
use Rawilk\LaravelCasters\Support\Name;
use Rawilk\ProfileFilament\Auth\Multifactor\App\Contracts\HasAppAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Contracts\HasMultiFactorAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Contracts\HasMultiFactorAuthenticationRecovery;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Contracts\HasWebauthn;
use Rawilk\Settings\Models\HasSettings;
use Rawilk\Settings\Support\Context;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAvatar, HasMultiFactorAuthentication, HasMultiFactorAuthenticationRecovery, HasName, HasSingleNameColumn, HasWebauthn, MustVerifyEmail
{
    use Concerns\HasAvatar;
    use Concerns\MultiFactorAuthenticatable;
    use HasCaseInsensitiveEmail;
    use HasFactory;
    use HasSettings;
    use Notifiable;
    use UsesHumanKeys;

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_enabled',
        'github_id',
        'is_admin',
        'preferred_mfa_provider',
    ];

    public static function humanKeyPrefix(): string
    {
        return 'usr';
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->isAdmin(),
            default => false,
        };
    }

    public function getFilamentName(): string
    {
        return $this->name->full;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function context(): Context
    {
        return new Context([
            'model' => $this::class,
            'id' => $this->getRouteKey(),
        ]);
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return "user.{$this->getRouteKey()}";
    }

    protected static function booted(): void
    {
        static::created(fn () => cache()->forget('users.count'));
        static::deleted(fn () => cache()->forget('users.count'));
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'name' => Name::class,
            'is_admin' => 'boolean',
        ];
    }
}
