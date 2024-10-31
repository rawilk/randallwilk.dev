<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProgrammingLanguage;
use App\Enums\RepositorySort;
use App\Enums\RepositoryType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Repository extends Model
{
    use Concerns\UsesHumanKeys;
    use HasFactory;
    use SoftDeletes;

    public static function humanKeyPrefix(): string
    {
        return 'repo';
    }

    public function getRouteKeyName(): string
    {
        return 'name';
    }

    public function isPackage(): bool
    {
        return $this->type === RepositoryType::Package;
    }

    public function isNpmPackage(): bool
    {
        return $this->language === ProgrammingLanguage::JavaScript;
    }

    public function setTopics(Collection $topics): static
    {
        $this->fill(['topics' => $topics->toArray()])->save();

        return $this;
    }

    public function scopeByType(Builder $query, ?RepositoryType $type): void
    {
        $query->when(
            $type !== null,
            fn (Builder $query) => $query->where($query->qualifyColumn('type'), $type),
        );
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where($query->qualifyColumn('visible'), true);
    }

    public function scopeSearch(Builder $query, ?string $search): void
    {
        if (! $search) {
            return;
        }

        $query->whereLike($query->qualifyColumn('name'), "%{$search}%");
    }

    public function scopeApplySort(Builder $query, ?string $sort = null): void
    {
        /** @var RepositorySort $enum */
        $enum = rescue(fn () => RepositorySort::tryFrom(ltrim($sort, '-')));
        if (! $enum) {
            return;
        }

        $query->orderBy(
            $query->qualifyColumn($enum->value),
            Str::startsWith($sort, '-') ? 'desc' : 'asc',
        );
    }

    /**
     * If the package has a scoped namespace, we need to use that instead.
     */
    public function nameForNpm(): string
    {
        return $this->scoped_name ?? $this->name;
    }

    protected static function booted(): void
    {
        static::updating(function (self $repository) {
            if ($repository->isDirty(['type', 'visible'])) {
                cache()->forget('repos.visible_count');
            }
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => "rawilk/{$this->name}",
        );
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (): string => "https://github.com/rawilk/{$this->name}",
        )->shouldCache();
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn (null|string|RepositoryType $value) => match (true) {
                default => null,
                is_string($value) => RepositoryType::tryFrom($value),
                $value instanceof RepositoryType => $value,
            },
        )->shouldCache();
    }

    protected function casts(): array
    {
        return [
            'downloads' => 'integer',
            'highlighted' => 'boolean',
            'language' => ProgrammingLanguage::class,
            'new' => 'boolean',
            'repository_created_at' => 'immutable_datetime',
            'stars' => 'integer',
            'topics' => 'array',
            'visible' => 'boolean',
        ];
    }
}
