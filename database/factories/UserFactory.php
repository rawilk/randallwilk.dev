<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
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
            'github_id' => null,
            'github_username' => null,
        ];
    }

    public function withMfa(): static
    {
        return $this->state([
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => Crypt::encryptString(
                json_encode([
                    'code-one',
                ])
            ),
        ]);
    }

    public function admin(): static
    {
        return $this->state(['is_admin' => true]);
    }
}
