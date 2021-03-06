<?php

namespace App\Console;

use App\Console\Commands\Docs\ImportDocsFromRepositoriesCommand;
use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\Github\ImportGithubIssuesCommand;
use App\Console\Commands\Github\ImportGithubRepositoriesCommand;
use App\Console\Commands\Github\ImportPackagistDownloadsCommand;
use App\Console\Commands\Npm\ImportNpmDownloadsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ImportGithubIssuesCommand::class)->hourly();
        $schedule->command(ImportPackagistDownloadsCommand::class)->hourly();
        $schedule->command(ImportNpmDownloadsCommand::class)->weekly();
        $schedule->command(ImportGithubRepositoriesCommand::class)->weekly();

        $schedule->command(ImportDocsFromRepositoriesCommand::class)->runInBackground()->everyThirtyMinutes();

        $schedule->command(GenerateSitemapCommand::class)->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
