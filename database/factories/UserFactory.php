<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'timezone' => fake()->timezone(),
            'password' => 'secret',
            'remember_token' => Str::random(10),
            'is_admin' => false,
            'avatar_path' => null,
            'two_factor_enabled' => false,
            'two_factor_recovery_codes' => null,
            'preferred_mfa_provider' => null,
            'email_verified_at' => now(),
        ];
    }

    public function withMfa(): static
    {
        return $this->state([
            'two_factor_enabled' => true,
        ]);
    }

    public function hasRecoveryCodes(): self
    {
        return $this->state([
            'two_factor_recovery_codes' => collect([
                'one',
                'two',
                'three',
                'four',
            ])->map(fn (string $code) => Hash::make($code)),
        ]);
    }

    public function admin(): static
    {
        return $this->state(['is_admin' => true]);
    }
}
