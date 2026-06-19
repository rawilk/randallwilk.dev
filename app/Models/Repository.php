<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProgrammingLanguage;
use App\Enums\RepositoryType;
use App\Models\Concerns\UsesHumanKeys;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Repository extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesHumanKeys;

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

    public function isVisible(): bool
    {
        return $this->getAttribute('visible') === true;
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

    #[Scope]
    protected function visible(Builder $query): void
    {
        $query->where($query->qualifyColumn('visible'), true);
    }

    #[Scope]
    protected function byType(Builder $query, ?RepositoryType $type): void
    {
        $query->when(
            $type !== null,
            fn (Builder $query) => $query->where($query->qualifyColumn('type'), $type),
        );
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
