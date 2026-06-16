<?php

declare(strict_types=1);

namespace App\Support\Auth;

use Closure;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Override;
use SensitiveParameter;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     * -> Overridden to handle case-insensitive email query for pgsql.
     *
     * @return (\Illuminate\Contracts\Auth\Authenticatable&\Illuminate\Database\Eloquent\Model)|null
     */
    #[Override]
    public function retrieveByCredentials(#[SensitiveParameter] array $credentials)
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if ($key === 'email') {
                $query->emailInsensitive($value);

                continue;
            }

            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } elseif ($value instanceof Closure) {
                $value($query);
            } else {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    protected function newModelQuery($model = null): Builder
    {
        $query = parent::newModelQuery($model);

        $query->withoutGlobalScopes();

        return $query;
    }
}
