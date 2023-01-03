<?php

declare(strict_types=1);

namespace App\Models\GitHub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GitHub\Issue
 *
 * @property int $id
 * @property int $repository_id
 * @property string $number
 * @property string $title
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GitHub\Repository $repository
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Issue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Issue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Issue query()
 *
 * @mixin \Eloquent
 */
final class Issue extends Model
{
    protected $guarded = ['id'];

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
