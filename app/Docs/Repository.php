<?php

declare(strict_types=1);

namespace App\Docs;

use Illuminate\Support\Collection;

class Repository
{
    public ?string $category;

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
        return once(function (): bool {
            if (! $this->config) {
                return false;
            }

            return data_get($this->config, 'archived', false);
        });
    }
}
