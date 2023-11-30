<?php

declare(strict_types=1);

namespace App\Models\GitHub;

use App\Enums\ProgrammingLanguageEnum;
use App\Enums\RepositorySortEnum;
use App\Enums\RepositoryTypeEnum;
use App\Support\Formatting\ShortNumberFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Rawilk\HumanKeys\Concerns\HasHumanKey;
use Rawilk\LaravelBase\Concerns\HasDatesForHumans;

class Repository extends Model
{
    use HasDatesForHumans;
    use HasHumanKey;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'downloads' => 'integer',
        'highlighted' => 'boolean',
        'language' => ProgrammingLanguageEnum::class,
        'new' => 'boolean',
        'repository_created_at' => 'immutable_datetime',
        'stars' => 'integer',
        'topics' => 'array',
        'visible' => 'boolean',
    ];

    public function getUrlAttribute(): string
    {
        return "https://github.com/rawilk/{$this->name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->scoped_name ?? $this->name;
    }

    public function getShowUrlAttribute(): string
    {
        return route('admin.repositories.show', $this);
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
        return $this->type === RepositoryTypeEnum::PACKAGE;
    }

    public function isNpmPackage(): bool
    {
        return $this->language === ProgrammingLanguageEnum::JAVASCRIPT;
    }

    public function hasDocs(): bool
    {
        return ! is_null($this->documentation_url);
    }

    public function getTypeAttribute(null|string|RepositoryTypeEnum $type): ?RepositoryTypeEnum
    {
        if (is_null($type)) {
            return null;
        }

        if ($type instanceof RepositoryTypeEnum) {
            return $type;
        }

        return RepositoryTypeEnum::tryFrom($type);
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

    public function scopeApplySort(Builder $query, string $sort = null): void
    {
        /** @var \App\Enums\RepositorySortEnum $enum */
        $enum = rescue(fn () => RepositorySortEnum::tryFrom(ltrim($sort, '-')));
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

    public function getRouteKeyName(): string
    {
        return 'h_key';
    }

    public function humanKeys(): array
    {
        return ['h_key'];
    }

    public static function humanKeyPrefix(): string
    {
        return 'repo';
    }

    protected static function booted(): void
    {
        self::updating(function (self $repository) {
            if ($repository->isDirty(['type', 'visible'])) {
                Cache::forget('repos.visible_count');
            }
        });
    }
}
