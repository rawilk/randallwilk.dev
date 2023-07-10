<?php

declare(strict_types=1);

namespace App\Models\Imports;

use Database\Factories\ImportFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rawilk\LaravelBase\Contracts\Models\Import as ImportContract;

class Import extends Model implements ImportContract
{
    use HasFactory;
    use HasUuids;

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
