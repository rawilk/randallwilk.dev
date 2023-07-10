<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Enums\PermissionEnum;
use App\Models\Imports\Import;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Rawilk\LaravelBase\Concerns\HasAvatar;
use Rawilk\LaravelBase\Concerns\HasDatesForHumans;
use Rawilk\LaravelBase\Concerns\Impersonatable;
use Rawilk\LaravelBase\Concerns\TwoFactorAuthenticatable;
use Rawilk\LaravelBase\Models\Role;
use Rawilk\LaravelBase\Scopes\SuperAdminScope;
use Rawilk\LaravelCasters\Casts\Password;
use Rawilk\LaravelCasters\Support\Name;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasAvatar;
    use HasRoles {
        hasRole as hasRolesHasRole;
        roles as hasRolesRoles;
    }
    use HasDatesForHumans;
    use Impersonatable;
    use TwoFactorAuthenticatable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_enabled',
    ];

    protected $casts = [
        'password' => Password::class,
        'name' => Name::class,
        'two_factor_enabled' => 'boolean',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function hasRole($roles, string $guard = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->hasRolesHasRole($roles, $guard);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles->containsStrict('name', Role::$superAdminName);
    }

    public function roles(): BelongsToMany
    {
        return $this->hasRolesRoles()->withoutGlobalScopes();
    }

    public function getEditUrlAttribute(): string
    {
        return route('admin.users.edit', $this);
    }

    public function getAbilitiesUrlAttribute(): string
    {
        return route('admin.users.edit.abilities', $this);
    }

    public function canImpersonate(): bool
    {
        return $this->hasPermissionTo(PermissionEnum::USERS_IMPERSONATE->value);
    }

    public function canBeImpersonated(?Authenticatable $impersonator = null): bool
    {
        return $impersonator?->can('impersonate', $this) ?? false;
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

    protected static function booted(): void
    {
        // Prevent non-super admin users from seeing super admin users.
        self::addGlobalScope(new SuperAdminScope);

        self::created(fn () => Cache::forget('users.count'));
        self::deleted(fn () => Cache::forget('users.count'));
    }
}
