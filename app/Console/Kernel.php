<?php

namespace App\Console;

use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\GitHub\ImportDocsFromRepositoriesCommand;
use App\Console\Commands\GitHub\ImportGitHubRepositoriesCommand;
use App\Console\Commands\GitHub\ImportPackagistDownloadsCommand;
use App\Console\Commands\Npm\ImportNpmDownloadsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ImportGitHubRepositoriesCommand::class)->weekly();
        $schedule->command(ImportPackagistDownloadsCommand::class)->hourly();
        $schedule->command(ImportNpmDownloadsCommand::class)->weekly();
        $schedule->command(GenerateSitemapCommand::class)->daily();
        $schedule->command(ImportDocsFromRepositoriesCommand::class)->everyThirtyMinutes()->runInBackground();

        // Model pruning...
        $schedule->command('queue:prune-batches --hours=48 --unfinished=72')->daily()->runInBackground();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
