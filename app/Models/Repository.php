<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProgrammingLanguage;
use App\Enums\RepositorySort;
use App\Enums\RepositoryType;
use App\Support\Formatting\ShortNumberFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Repository extends Model
{
    use Concerns\UsesHumanKeys;
    use HasUuids;
    use SoftDeletes;

    public static function humanKeyPrefix(): string
    {
        return 'repo';
    }

    public function getUrlAttribute(): string
    {
        return "https://github.com/rawilk/{$this->name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->scoped_name ?? $this->name;
    }

    public function getDownloadsForHumansAttribute(): string
    {
        return number_format($this->downloads);
    }

    public function getDownloadsForFrontAttribute(): string
    {
        return ShortNumberFormatter::of($this->downloads)->format();
    }

    public function getStarsForHumansAttribute(): string
    {
        return number_format($this->stars);
    }

    public function getStarsForFrontAttribute(): string
    {
        return ShortNumberFormatter::of($this->stars)->format();
    }

    public function isPackage(): bool
    {
        return $this->type === RepositoryType::Package;
    }

    public function isNpmPackage(): bool
    {
        return $this->language === ProgrammingLanguage::JavaScript;
    }

    public function hasDocs(): bool
    {
        return filled($this->documentation_url);
    }

    public function getTypeAttribute(null|string|RepositoryType $type): ?RepositoryType
    {
        if (is_null($type)) {
            return null;
        }

        if ($type instanceof RepositoryType) {
            return $type;
        }

        return RepositoryType::tryFrom($type);
    }

    public function getFullNameAttribute(): string
    {
        return "rawilk/{$this->name}";
    }

    public function setTopics(Collection $topics): self
    {
        $this->forceFill(['topics' => $topics->toArray()])->save();

        return $this;
    }

    public function scopeByType(Builder $query, ?string $type): void
    {
        if (! $type) {
            return;
        }

        $query->when(
            $type !== 'missing',
            fn ($query) => $query->where('type', $type),
            fn ($query) => $query->whereNull('type'),
        );
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where('visible', true);
    }

    public function scopeApplySort(Builder $query, ?string $sort = null): void
    {
        /** @var RepositorySort $enum */
        $enum = rescue(fn () => RepositorySort::tryFrom(ltrim($sort, '-')));
        if (! $enum) {
            return;
        }

        $query->orderBy(
            $enum->value,
            Str::startsWith($sort, '-') ? 'desc' : 'asc',
        );
    }

    public function getTypeBackgroundColorAttribute(): string
    {
        return $this->type?->bgColor() ?? 'bg-gray-200';
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
        self::updating(function (self $repository) {
            if ($repository->isDirty(['type', 'visible'])) {
                Cache::forget('repos.visible_count');
            }
        });
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
