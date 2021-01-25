<?php

namespace App\Docs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class Repository
{
    /** @var \Illuminate\Support\Collection|\App\Docs\Alias[] */
    public Collection $aliases;

    public null|string $category;
    private null|string $icon = null;
    private null|bool $archived = null;

    public function __construct(public string $slug, Collection $aliases, DocumentationPage $index)
    {
        $this->aliases = $aliases->sortByDesc('slug');
        $this->category = $index->category ?? null;
    }

    public function getAlias(string $alias): ?Alias
    {
        return $this->aliases->firstWhere('slug', $alias);
    }

    public function getIcon(): null|string
    {
        if (! is_null($this->icon)) {
            return $this->icon;
        }

        return $this->icon = match(strtolower($this->category)) {
            'laravel', 'vue' => strtolower($this->category),
            default => null,
        };
    }

    public function iconClass(): null|string
    {
        return match($this->getIcon()) {
            'laravel' => 'bg-red-50 text-red-700',
            'vue' => 'bg-green-50 text-green-700',
        };
    }

    public function isArchived(): bool
    {
        if (! is_null($this->archived)) {
            return $this->archived;
        }

        $config = collect(Config::get('docs.repositories'))
            ->filter(fn (array $config) => $config['name'] === $this->slug)
            ->first();

        if (! $config) {
            return $this->archived = false;
        }

        return $this->archived = $config['archived'] ?? false;
    }
}
