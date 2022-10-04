<?php

use App\Models\User\User;
use Rawilk\LaravelBase\Models\Role;
use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// Helpers...
function adminUser(): User
{
    $user = User::factory()->create();

    $user->assignRole(Role::$adminName);

    return $user;
}

function superAdmin(): User
{
    $user = User::factory()->create();

    $user->assignRole(Role::$superAdminName);

    return $user;
}

function regularUser(): User
{
    $user = User::factory()->create();

    $user->assignRole(Role::$userName);

    return $user;
}
