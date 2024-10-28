<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NewUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createSuperAdminUser();
    }

    private function createSuperAdminUser(): void
    {
        // We've already seeded the admin user, no need to do it again.
        if (User::withoutGlobalScopes()->where('is_admin', true)->exists()) {
            return;
        }

        User::factory()->admin()->create([
            'name' => config('randallwilk.dev_credentials.name'),
            'email' => config('randallwilk.dev_credentials.email'),
            'timezone' => config('randallwilk.timezone'),
            'password' => 'secret',
            'remember_token' => null,
        ]);
    }
}
