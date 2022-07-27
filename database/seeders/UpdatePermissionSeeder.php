<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class UpdatePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            NewPermissionSeeder::class,
        ]);
    }
}
