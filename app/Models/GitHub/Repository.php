<?php

declare(strict_types=1);

namespace App\Models\GitHub;

use App\Enums\ProgrammingLanguageEnum;
use App\Enums\RepositoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property RepositoryTypeEnum|null $type
 * @property ProgrammingLanguageEnum|null $language
 * @property bool $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $created_at_for_humans
 * @property-read string $downloads_for_humans
 * @property-read string $stars_for_humans
 * @property-read string|null $updated_at_for_humans
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GitHub\Issue[] $issues
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Repository newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Repository newQuery()
 * @method static \Illuminate\Database\Query\Builder|Repository onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Repository query()
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
        'type' => RepositoryTypeEnum::class,
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

    public function getDownloadsForHumansAttribute(): string
    {
        return number_format($this->downloads);
    }

    public function getStarsForHumansAttribute(): string
    {
        return number_format($this->stars);
    }

    public function isNpmPackage(): bool
    {
        return $this->language === ProgrammingLanguageEnum::JAVASCRIPT;
    }
}
