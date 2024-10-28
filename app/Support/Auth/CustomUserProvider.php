<?php

declare(strict_types=1);

namespace App\Support\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Builder;

class CustomUserProvider extends EloquentUserProvider
{
    protected function newModelQuery($model = null): Builder
    {
        $query = parent::newModelQuery($model);

        $query->withoutGlobalScopes();

        return $query;
    }
}
