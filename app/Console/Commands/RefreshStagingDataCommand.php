<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;
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

        $productionConnectionName = config('database.default');
        config()->set("database.connections.{$productionConnectionName}.dump", [
            'addExtraOption' => $this->buildDumpOptions(),
        ]);

        $this->call(CreateSnapshotCommand::class, [
            'name' => $snapshotName = 'dump-' . now()->unix(),
            '--connection' => $productionConnectionName,
            '--compress' => true,
        ]);

        $this->components->info('Loading production dump into staging database...');

        $this->call(LoadSnapshotCommand::class, [
            'name' => $snapshotName,
            '--force' => true,
            '--connection' => 'staging',
            '--stream' => true,
        ]);

        $this->components->info('Cleaning up...');

        $this->call(DeleteSnapshotCommand::class, [
            'name' => $snapshotName,
        ]);
    }

    protected function buildDumpOptions(): string
    {
        $options = [
            // Our production & staging databases have different database
            // users that own them.
            '--no-owner',
            '--no-privileges',

            // Exclude data from certain tables, so we don't
            // need to bother truncating them later.
            ...array_map(
                fn (string $table) => "--exclude-table-data={$table}",
                config('randallwilk.staging.exclude_tables', []),
            ),
        ];

        return implode(' ', $options);
    }

    protected function confirmToProceed(): bool
    {
        return confirm(
            'This will overwrite data in your staging database. Are you sure you want to continue?',
        );
    }
}
