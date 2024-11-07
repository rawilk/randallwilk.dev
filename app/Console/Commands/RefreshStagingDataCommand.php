<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;
use Illuminate\Support\Facades\DB;
use Spatie\DbSnapshots\Commands\Create as CreateSnapshotCommand;
use Spatie\DbSnapshots\Commands\Delete as DeleteSnapshotCommand;
use Spatie\DbSnapshots\Commands\Load as LoadSnapshotCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

use function Laravel\Prompts\confirm;

class RefreshStagingDataCommand extends Command
{
    use Prohibitable;

    protected $signature = 'app:refresh-staging-data';

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
            trim('
            Staging database data has been refreshed.
            Remember to redact sensitive information in the staging environment and run migrations if necessary.
            ')
        );

        return SymfonyCommand::SUCCESS;
    }

    protected function cloneProductionToStaging(): void
    {
        $this->components->info('Dumping production database...');

        $this->call(CreateSnapshotCommand::class, [
            'name' => $snapshotName = 'dump-' . now()->unix(),
            '--compress' => true,
        ]);

        $this->components->info('Loading production dump into staging database...');

        $this->call(LoadSnapshotCommand::class, [
            'name' => $snapshotName,
            '--connection' => 'staging',
        ]);

        $this->truncateExcludedTables();

        $this->components->info('Cleaning up...');

        $this->call(DeleteSnapshotCommand::class, [
            'name' => $snapshotName,
        ]);
    }

    protected function truncateExcludedTables(): void
    {
        DB::reconnect();

        $connection = DB::connection('staging');

        $this->components->info('Truncating excluded staging tables...');

        foreach (config('randallwilk.staging.exclude_tables', []) as $table) {
            $this->line('Truncating: ' . $table);

            $connection->table($table)->truncate();
        }
    }

    protected function confirmToProceed(): bool
    {
        return confirm(
            'This will overwrite data in your staging database. Are you sure you want to continue?',
        );
    }
}
