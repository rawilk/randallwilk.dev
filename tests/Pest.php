<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

pest()->extend(Tests\TestCase::class)->in(
    'Feature',
    'Unit',
    'Arch',
);

pest()->uses(
    LazilyRefreshDatabase::class,
)->in(
    'Feature',
);

// Helpers

function adminUser(array $attributes = []): User
{
    return User::factory()->admin()->create($attributes);
}

function getPasswordResetToken(User $user): string
{
    return tap(
        hash_hmac('sha256', Str::random(40), config('app.key')),
        function (string $token) use ($user) {
            DB::table(config('auth.passwords.users.table'))->insert([
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]);
        },
    );
}

function passwordResetUrl(string $token, string $email): string
{
    return URL::temporarySignedRoute(
        name: filament()->getCurrentPanel()->generateRouteName('auth.password-reset.reset'),
        expiration: now()->addMinutes(config('auth.passwords.users.expire')),
        parameters: [
            'token' => $token,
            'email' => $email,
        ],
    );
}

function sudoChallengeTitle(): string
{
    return __('profile-filament::messages.sudo_challenge.title');
}
