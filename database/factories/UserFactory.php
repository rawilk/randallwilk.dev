<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
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
        ];
    }

    public function admin(): static
    {
        return $this->state(['is_admin' => true]);
    }
}
