<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait HasCaseInsensitiveEmail
{
    #[Scope]
    protected function emailInsensitive(Builder $query, ?string $email): void
    {
        $column = $query->qualifyColumn('email');

        $query->whereRaw("LOWER({$column}) = LOWER(?)", [$email ?? '']);
    }
}
