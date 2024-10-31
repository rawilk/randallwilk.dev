<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProgrammingLanguage;
use App\Enums\RepositoryType;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Repository>
 */
class RepositoryFactory extends Factory
{
    protected $model = Repository::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'topics' => [fake()->word(), fake()->word()],
            'stars' => fake()->numberBetween(0, 10000),
            'downloads' => fake()->numberBetween(0, 10000),
            'repository_created_at' => now()->subYear(),
            'new' => false,
            'highlighted' => false,
            'type' => fake()->randomElement(RepositoryType::cases()),
            'language' => ProgrammingLanguage::Php,
            'visible' => true,
        ];
    }

    public function package(): static
    {
        return $this->state(['type' => RepositoryType::Package]);
    }

    public function project(): static
    {
        return $this->state(['type' => RepositoryType::Project]);
    }
}
