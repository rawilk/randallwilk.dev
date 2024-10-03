<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Imports\Import;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Rawilk\LaravelCasters\Support\Name;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    //    use HasAvatar;
    //    use HasDatesForHumans;
    use Concerns\UsesHumanKeys;
    use HasFactory;
    use HasUuids;

    //    use Impersonatable;
    use Notifiable;
    //    use TwoFactorAuthenticatable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function humanKeyPrefix(): string
    {
        return 'usr';
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
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
        return null;
    }

    protected static function booted(): void
    {
        // Prevent non-super admin users from seeing super admin users.
        //        self::addGlobalScope(new SuperAdminScope);

        self::created(fn () => Cache::forget('users.count'));
        self::deleted(fn () => Cache::forget('users.count'));
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
