<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories;

use App\Jobs\Repositories\ImportNpmDownloadsJob;
use App\Jobs\Repositories\ImportPackagistDownloadsJob;
use App\Jobs\Repositories\ImportRepositoriesJob;
use App\Models\Repository;
use App\Notifications\Repositories\ManualRepositorySyncFinishedNotification;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;

class SyncRepositoryInfoAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/view.actions.sync.label'));

        $this->icon('phosphor-arrows-clockwise');

        $this->authorize(fn (): bool => Gate::allows('manage', Repository::class));

        $this->successNotificationTitle(__('repositories/view.actions.sync.success'));

        $this->action(function (Repository $record) {
            $jobs = array_filter([
                new ImportRepositoriesJob(config('services.github.username'), $record->name),
                $this->getSyncImportsJob($record),
            ]);

            /** @var \App\Models\User $user */
            $user = Filament::auth()->user()->withoutRelations();

            Bus::batch([$jobs])
                ->finally(function (Batch $batch) use ($user) {
                    $user->notify(
                        new ManualRepositorySyncFinishedNotification($batch->id, $batch->name),
                    );
                })
                ->name("manual_repo_sync:{$record->name}")
                ->dispatch();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'sync';
    }

    protected function getSyncImportsJob(Repository $record): null|ImportPackagistDownloadsJob|ImportNpmDownloadsJob
    {
        if (! $record->isPackage()) {
            return null;
        }

        if ($record->isNpmPackage()) {
            return new ImportNpmDownloadsJob($record->nameForNpm());
        }

        return new ImportPackagistDownloadsJob(config('services.github.username'), $record->name);
    }
}
