<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Access\Permission;
use App\Models\Access\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

final class NewPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionEnum::cases() as $permission) {
            Permission::updateOrCreate(['name' => $permission->value]);
        }

        // Update our "Admin" role if it exists to have all permissions.
        if ($role = Role::withoutGlobalScopes()->where('name', Role::$adminName)->first()) {
            $role->giveAllPermissions();
        }
    }
}
