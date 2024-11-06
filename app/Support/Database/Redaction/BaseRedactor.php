<?php

declare(strict_types=1);

namespace App\Support\Database\Redaction;

use App\Support\Database\Redaction\Contracts\Redactor;
use Illuminate\Console\OutputStyle;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class BaseRedactor implements Redactor
{
    protected ?OutputStyle $output = null;

    protected ?Factory $outputComponents = null;

    abstract protected function query(): Builder;

    abstract protected function redactRecord(Model $record): Model;

    public function handle(): void
    {
        $model = $this->query()->getModel();

        $this->outputComponents?->info("Redacting: {$model->getTable()}");

        $model::withoutTimestamps(function () {
            $bar = $this->output?->createProgressBar($this->query()->count());

            $this
                ->query()
                ->chunkById($this->chunkSize(), function (Collection $chunk) use ($bar) {
                    $records = $chunk
                        ->map(fn (Model $record) => $this->redactRecord($record))
                        ->mapWithKeys(fn (Model $record) => [$record->getKey() => $record->getDirty()])
                        ->filter(fn (array $attributes) => filled($attributes));

                    if ($records->isEmpty()) {
                        return;
                    }

                    $this
                        ->query()
                        ->whereKey($records->keys())
                        ->update($this->buildUpdateCases($records));

                    $bar?->advance($chunk->count());
                });

            $bar?->finish();
        });

        $this->output?->newLine(2);
    }

    public function setOutput(OutputStyle $output): void
    {
        $this->output = $output;
    }

    public function setOutputComponents(Factory $outputComponents): void
    {
        $this->outputComponents = $outputComponents;
    }

    protected function buildUpdateCases(Collection $records): array
    {
        // Get all unique columns that need updating across all records.
        $columns = $records
            ->flatMap(fn (array $attributes) => array_keys($attributes))
            ->unique()
            ->values();

        return $columns->mapWithKeys(function (string $column) use ($records) {
            $cases = $records->map(function (array $attributes, int|string $id) use ($column) {
                // Only include this record in the case if the column was modified.
                if (! array_key_exists($column, $attributes)) {
                    return null;
                }

                $value = $attributes[$column] === null
                    ? 'NULL'
                    : "'" . addslashes($attributes[$column]) . "'";

                return "WHEN {$id} THEN {$value}";
            })->filter()->values();

            if ($cases->isEmpty()) {
                return [];
            }

            return [
                $column => DB::raw(
                    'CASE id ' .
                    $cases->implode(' ') .
                    " ELSE {$column} END"
                ),
            ];
        })->all();
    }

    protected function chunkSize(): int
    {
        return 500;
    }

    protected function maskEmail(?string $email): ?string
    {
        if (! $email) {
            return null;
        }

        if (! Str::contains($email, '@')) {
            return Str::mask($email, '*', 2);
        }

        [$handle, $domain] = explode('@', $email);

        $start = strlen($handle) <= 3 ? 1 : 3;

        return Str::mask($handle, '*', $start) . '@' . Str::mask($domain, '*', 3);
    }

    protected function maskValue(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $length = strlen($value);

        if ($length === 1) {
            return '*';
        }

        if ($length <= 3) {
            return Str::mask($value, '*', 1);
        }

        $start = $length <= 5 ? 1 : 2;

        return Str::mask($value, '*', $start, -1);
    }
}
