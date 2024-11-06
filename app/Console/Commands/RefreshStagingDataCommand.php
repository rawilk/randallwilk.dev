<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Database\DbDumper;
use App\Support\Database\PostgreDumpImporter;
use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

use function Laravel\Prompts\confirm;

class RefreshStagingDataCommand extends Command
{
    use Prohibitable;

    protected $signature = 'app:refresh-staging-data
                            {--only=* : Only clone the specified tables}
                            {--exclude=* : Tables to exclude from cloning}
    ';

    protected $description = 'Clone production database to staging';

    public function handle(): int
    {
        if (
            $this->isProhibited() ||
            (! $this->confirmToProceed())
        ) {
            return SymfonyCommand::FAILURE;
        }

        $this->cloneProductionToStaging();

        $this->components->success(
            'Staging database data has been refreshed. Remember to redact sensitive information in the staging environment and run migrations if necessary.'
        );

        return SymfonyCommand::SUCCESS;
    }

    protected function cloneProductionToStaging(): void
    {
        $this->components->info('Dumping production database...');

        $dumper = DbDumper::make(compress: false)->resolve();
        $file = __DIR__ . '/dump.sql';

        $dumper->excludeTables(
            collect($this->option('exclude'))
                ->merge(config('randallwilk.staging.exclude_tables'))
                ->all()
        );

        $dumper->doNotCreateTables();

        $dumper->dumpToFile($file);

        $this->truncateRelevantTables();

        $this->importDumpIntoStaging($file);

        File::delete($file);
    }

    protected function truncateRelevantTables(): void
    {
        $this->components->info('Truncating relevant staging tables...');

        foreach ($this->tablesToClone() as $table) {
            $this->stagingDb()->table($table)->truncate();
        }
    }

    protected function importDumpIntoStaging(string $filename): void
    {
        $this->components->info('Importing production dump into staging database...');

        PostgreDumpImporter::fromDumper(
            DbDumper::make(connection: 'staging', compress: false),
        )->import($filename);
    }

    protected function confirmToProceed(): bool
    {
        return confirm(
            'This will overwrite data in your staging database. Are you sure you want to continue?',
        );
    }

    protected function tablesToClone(): array
    {
        if ($only = $this->option('only')) {
            return $only;
        }

        $allTables = Schema::connection(config('database.default'))->getTableListing();
        $exclude = collect($this->option('exclude'))->merge(config('randallwilk.staging.exclude_tables'));

        return collect($allTables)
            ->filter(function (string $table) use ($exclude) {
                if ($exclude->isNotEmpty()) {
                    return ! $exclude->contains($table);
                }

                return true;
            })
            ->all();
    }

    protected function stagingDb(): Connection
    {
        return once(fn () => DB::connection('staging'));
    }
}
