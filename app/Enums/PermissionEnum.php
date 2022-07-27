<?php

declare(strict_types=1);

namespace App\Enums;

use RuntimeException;

enum PermissionEnum: string
{
    // Permissions...
    case PERMISSIONS_ASSIGN = 'permissions.assign';

    // Roles...
    case ROLES_CREATE = 'roles.create';
    case ROLES_EDIT = 'roles.edit';
    case ROLES_DELETE = 'roles.delete';
    case ROLES_ASSIGN = 'roles.assign';

    // Users...
    case USERS_CREATE = 'users.create';
    case USERS_EDIT = 'users.edit';
    case USERS_DELETE = 'users.delete';
    case USERS_IMPERSONATE = 'users.impersonate';

    public function description(): string
    {
        $description = __("enums.permission.{$this->value}");

        throw_unless($description, RuntimeException::class, "No description provided yet for: {$this->name}!");

        return $description;
    }
}
