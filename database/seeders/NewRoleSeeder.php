<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Rawilk\LaravelBase\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class NewRoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->getRolesToSeed() as $data) {
            if (Role::withoutGlobalScopes()->where('name', $data['name'])->exists()) {
                continue;
            }

            $role = Role::create($data);

            if ($role->name === Role::$adminName) {
                $role->giveAllPermissions();
            }
        }
    }

    private function getRolesToSeed(): array
    {
        return [
            [
                'name' => Role::$superAdminName,
                'description' => 'The super admin role has unrestricted access to anything on the site.',
            ],
            [
                'name' => Role::$adminName,
                'description' => 'The administrator role has a high level of access to the site.',
            ],
            [
                'name' => Role::$userName,
                'description' => 'The basic user account role.',
            ],
        ];
    }
}
