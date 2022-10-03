<?php

declare(strict_types=1);

namespace App\Models\GitHub;

use App\Enums\ProgrammingLanguageEnum;
use App\Enums\RepositorySortEnum;
use App\Enums\RepositoryTypeEnum;
use App\Support\Formatting\ShortNumberFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Concerns\HasDatesForHumans;

/**
 * App\Models\GitHub\Repository
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property array|null $topics
 * @property string|null $documentation_url
 * @property string|null $blogpost_url
 * @property int $stars
 * @property int|null $downloads
 * @property \Carbon\CarbonImmutable $repository_created_at
 * @property bool $new
 * @property bool $highlighted
 * @property \App\Enums\RepositoryTypeEnum|null $type
 * @property ProgrammingLanguageEnum|null $language
 * @property bool $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $created_at_for_humans
 * @property-read string $downloads_for_front
 * @property-read string $downloads_for_humans
 * @property-read string $full_name
 * @property-read string $show_url
 * @property-read string $stars_for_front
 * @property-read string $stars_for_humans
 * @property-read string $type_background_color
 * @property-read string|null $updated_at_for_humans
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GitHub\Issue[] $issues
 *
 * @method static Builder|Repository applySort(?string $sort = null)
 * @method static Builder|Repository byType(?string $type)
 * @method static Builder|Repository newModelQuery()
 * @method static Builder|Repository newQuery()
 * @method static \Illuminate\Database\Query\Builder|Repository onlyTrashed()
 * @method static Builder|Repository query()
 * @method static Builder|Repository visible()
 * @method static \Illuminate\Database\Query\Builder|Repository withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Repository withoutTrashed()
 * @mixin \Eloquent
 */
final class Repository extends Model
{
    use SoftDeletes;
    use HasDatesForHumans;

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

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
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

    public function scopeApplySort(Builder $query, ?string $sort = null): void
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

    protected static function booted(): void
    {
        self::updating(function (self $repository) {
            if ($repository->isDirty(['type', 'visible'])) {
                Cache::forget('repos.visible_count');
            }
        });
    }
}
