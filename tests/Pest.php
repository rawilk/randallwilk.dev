<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\AppConfig;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

pest()->uses(TestCase::class)->in(__DIR__);

pest()->uses(LazilyRefreshDatabase::class)->in('Feature');

// pest()->browser()->withHost('randallwilk.dev.test');

// Helpers

function adminUser(array $attributes = []): User
{
    return User::factory()->admin()->create($attributes);
}

function passwordResetUrl(string $token, string $email): string
{
    return URL::temporarySignedRoute(
        name: filament()->getCurrentPanel()->generateRouteName('auth.password-reset.reset'),
        expiration: now()->addMinutes(AppConfig::passwordResetDecayMinutes()),
        parameters: [
            'token' => $token,
            'email' => $email,
        ],
    );
}

function putAppInProduction(): void
{
    app()->detectEnvironment(fn () => 'production');
}
