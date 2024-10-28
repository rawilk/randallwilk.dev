<?php

declare(strict_types=1);

namespace App\Docs;

use Illuminate\Support\Collection;

class Repository
{
    public ?string $category;

    protected ?bool $archived = null;

    protected ?array $config;

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Docs\Alias>  $aliases
     */
    public function __construct(
        public string $slug,
        public Collection $aliases,
        ?DocumentationPage $index,
    ) {
        $this->category = $index?->category ?? null;

        $this->config = collect(config('docs.repositories'))
            ->filter(fn (array $config) => $config['name'] === $slug)
            ->first();
    }

    public function getAlias(string $alias): ?Alias
    {
        return $this->aliases->firstWhere('slug', $alias);
    }

    public function isArchived(): bool
    {
        if (! is_null($this->archived)) {
            return $this->archived;
        }

        if (! $this->config) {
            return $this->archived = false;
        }

        return $this->archived = $this->config['archived'] ?? false;
    }
}
