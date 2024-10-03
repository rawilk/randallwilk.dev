<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Imports\Import;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Rawilk\HumanKeys\Concerns\HasHumanKey;
use Rawilk\LaravelCasters\Support\Name;

class User extends Authenticatable
{
    //    use HasAvatar;
    //    use HasDatesForHumans;
    use HasFactory;
    use HasHumanKey;
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

    public function getRouteKeyName(): string
    {
        return 'h_key';
    }

    public function humanKeys(): array
    {
        return ['h_key'];
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
        ];
    }
}
