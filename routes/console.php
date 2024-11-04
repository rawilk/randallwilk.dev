<?php

declare(strict_types=1);

use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\GitHub\ImportDocsFromRepositoriesCommand;
use App\Console\Commands\GitHub\ImportGitHubRepositoriesCommand;
use App\Console\Commands\GitHub\ImportPackagistDownloadsCommand;
use App\Console\Commands\Npm\ImportNpmDownloadsCommand;
use Illuminate\Auth\Console\ClearResetsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ImportGitHubRepositoriesCommand::class)->weekly();
Schedule::command(ImportPackagistDownloadsCommand::class)->hourly();
Schedule::command(ImportNpmDownloadsCommand::class)->weekly();
Schedule::command(GenerateSitemapCommand::class)->daily();
Schedule::command(ImportDocsFromRepositoriesCommand::class)->everyThirtyMinutes()->runInBackground();

// Model pruning
Schedule::command('queue:prune-batches --hours=48 --unfinished=72')->daily()->runInBackground();
Schedule::command(ClearResetsCommand::class)->daily();
