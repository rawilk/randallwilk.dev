<?php

declare(strict_types=1);

namespace App\Support\ValueStores;

use Illuminate\Support\Arr;
use Spatie\Valuestore\Valuestore;

final class UpdatedRepositoriesValueStore
{
    private Valuestore $valueStore;

    public function __construct()
    {
        $this->valueStore = Valuestore::make(storage_path('app/updatedRepositories.json'));
    }

    public static function make(): self
    {
        return new self;
    }

    public function getNames(): array
    {
        return Arr::wrap($this->valueStore->get('updatedRepositoryNames') ?? []);
    }

    public function store(string $name): self
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
