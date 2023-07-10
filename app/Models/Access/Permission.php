<?php

declare(strict_types=1);

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Rawilk\LaravelBase\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use HasUuids;
}
