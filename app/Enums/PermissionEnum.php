<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Facades\Lang;
use RuntimeException;

enum PermissionEnum: string
{
    // Permissions...
    case PERMISSIONS_ASSIGN = 'permissions.assign';

    // Repositories...
    case REPOSITORIES_MANAGE = 'repositories.manage';

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
        throw_unless(Lang::has("enums.permission.{$this->value}"), RuntimeException::class, "No description provided yet for: {$this->value}!");

        return __("enums.permission.{$this->value}");
    }
}
