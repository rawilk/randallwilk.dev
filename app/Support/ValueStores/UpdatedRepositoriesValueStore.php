<?php

declare(strict_types=1);

namespace App\Support\ValueStores;

use Illuminate\Support\Arr;
use Spatie\Valuestore\Valuestore;

class UpdatedRepositoriesValueStore
{
    protected Valuestore $valueStore;

    public function __construct()
    {
        $this->valueStore = Valuestore::make(storage_path('app/updatedRepositories.json'));
    }

    public static function make(): static
    {
        return new static;
    }

    public function getNames(): array
    {
        return Arr::wrap($this->valueStore->get('updatedRepositoryNames') ?? []);
    }

    public function store(string $name): static
    {
        $updatedRepositoryNames = $this->valueStore->get('updatedRepositoryNames', []);

        $updatedRepositoryNames[] = $name;

        $this->valueStore->put('updatedRepositoryNames', array_unique($updatedRepositoryNames));

        return $this;
    }

    public function flush(): void
    {
        $this->valueStore->flush();
    }
}
