<?php

namespace Database\Factories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    private static ?array $roles = null;

    public function configure(): self
    {
        return $this->afterCreating(function (User $user) {
            if (is_array(self::$roles)) {
                $user->assignRole(self::$roles);

                self::$roles = null;
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'timezone' => fake()->timezone(),
            'password' => 'secret',
            'remember_token' => Str::random(10),
        ];
    }

    public function superAdmin(): self
    {
        $roleModel = app(config('permission.models.role'));
        $role = $roleModel::firstOrCreate(['name' => Role::$superAdminName], []);

        self::$roles = [$role];

        return $this;
    }
}
