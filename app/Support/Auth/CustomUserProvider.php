<?php

declare(strict_types=1);

namespace App\Support\Auth;

use Illuminate\Auth\EloquentUserProvider;

final class CustomUserProvider extends EloquentUserProvider
{
    protected function newModelQuery($model = null): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::newModelQuery($model);

        $query->withoutGlobalScopes();

        return $query;
    }
}
