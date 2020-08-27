<?php

namespace App\Models;

use App\Models\Enums\RepositoryType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Repository extends BaseModel
{
    protected $casts = [
        'new' => 'boolean',
        'visible' => 'boolean',
        'topics' => 'array',
        'highlighted' => 'boolean',
        'downloads' => 'integer',
        'stars' => 'integer',
    ];

    protected $with = ['issues'];

    protected $dates = ['repository_created_at'];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    public function getIssuesUrlAttribute(): string
    {
        return "{$this->url}/issues?=is%3Aopen+is%3Aissue";
    }

    public function hasIssues(): bool
    {
        return count($this->issues) > 0;
    }

    public function getSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getUrlAttribute(): string
    {
        return "https://github.com/rawilk/{$this->name}";
    }

    public function getFullNameAttribute(): string
    {
        return "rawilk/{$this->name}";
    }

    public function getLanguageColorAttribute(): string
    {
        $colors = [
            'PHP' => 'blue',
            'JavaScript' => 'orange',
        ];

        return $colors[$this->language] ?? 'gray';
    }

    public static function getTotalDownloads(): int
    {
        return static::sum('downloads');
    }

    public function setTopics(Collection $topics): self
    {
        $this->topics = $topics->toArray();

        $this->save();

        return $this;
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where('visible', true);
    }

    public function scopePackages(Builder $query): void
    {
        $query->where('type', RepositoryType::PACKAGE);
    }

    public function scopeProjects(Builder $query): void
    {
        $query->where('type', RepositoryType::PROJECT);
    }

    public function scopeHighlighted(Builder $query): void
    {
        $query->where('highlighted', true);
    }

    public function scopeSearch(Builder $query, string $search): void
    {
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
    }

    public function scopeApplySort(Builder $query, string $sort): void
    {
        if (! $sort) {
            return;
        }

        collect(['name', 'stars', 'repository_created_at', 'downloads'])
            ->first(static function (string $validSort) use ($sort) {
                return ltrim($sort, '-') === $validSort;
            }, static function () use ($sort) {
                throw new \BadMethodCallException("Not allowed to sort by '{$sort}'.");
            });

        $query->orderBy(
            ltrim($sort, '-'),
            Str::startsWith($sort, '-') ? 'desc' : 'asc'
        );
    }
}
