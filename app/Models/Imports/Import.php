<?php

declare(strict_types=1);

namespace App\Models\Imports;

use Database\Factories\ImportFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rawilk\LaravelBase\Contracts\Models\Import as ImportContract;

/**
 * App\Models\Imports\Import
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $model
 * @property string $import
 * @property string $file_path
 * @property string $file_name
 * @property int $total_rows
 * @property int $processed_rows
 * @property \Carbon\CarbonImmutable|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ImportFactory factory(...$parameters)
 * @method static Builder|Import forImport(string $import)
 * @method static Builder|Import forModel(string $model)
 * @method static Builder|Import newModelQuery()
 * @method static Builder|Import newQuery()
 * @method static Builder|Import notCompleted()
 * @method static Builder|Import query()
 * @mixin \Eloquent
 */
final class Import extends Model implements ImportContract
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'completed_at' => 'immutable_datetime',
        'total_rows' => 'integer',
        'processed_rows' => 'integer',
    ];

    public function scopeNotCompleted(Builder $query): void
    {
        $query->whereNull('completed_at');
    }

    public function scopeForModel(Builder $query, string $model): void
    {
        $query->where('model', $model);
    }

    public function scopeForImport(Builder $query, string $import): void
    {
        $query->where('import', $import);
    }

    public function percentageComplete(): int
    {
        return (int) floor(($this->processed_rows / $this->total_rows) * 100);
    }

    protected static function newFactory(): ImportFactory
    {
        return ImportFactory::new();
    }
}
