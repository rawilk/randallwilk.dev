<?php

declare(strict_types=1);

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Rawilk\LaravelBase\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasUuids;
}
