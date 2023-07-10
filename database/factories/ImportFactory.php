<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Imports\Import;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Imports\Import>
 */
final class ImportFactory extends Factory
{
    protected $model = Import::class;

    public function definition(): array
    {
        return [
            //
        ];
    }
}
