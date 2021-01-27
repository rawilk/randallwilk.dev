<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Builder::macro('search', function (array | string $field, string | null $search) {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            if (empty($search)) {
                return $this;
            }

            if (is_array($field)) {
                return $this->where(function ($query) use ($field, $search) {
                    foreach ($field as $searchField) {
                        $query->orWhere($searchField, 'LIKE', "%{$search}%");
                    }
                });
            }

            return $this->where($field, 'LIKE', "%{$search}%");
        });
    }
}
