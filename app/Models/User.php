<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasAvatar;
use App\Models\Concerns\LocalizesDates;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Rawilk\LaravelCasters\Casts\Password;
use Rawilk\LaravelCasters\Support\Name;

final class User extends Authenticatable
{
    use Notifiable, HasFactory;
    use LocalizesDates;
    use HasAvatar;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'bool',
        'password' => Password::class,
        'name' => Name::class,
    ];

    public function getEditUrlAttribute(): string
    {
        return route('admin.users.edit', $this);
    }

    public function impersonate(): void
    {
        if (! Session::has('impersonate')) {
            Session::put('impersonate', Auth::id());
        }

        Auth::loginUsingId($this->id);

        redirect()->route('profile.show');
    }

    protected static function booted(): void
    {
        self::observe(UserObserver::class);
    }
}
