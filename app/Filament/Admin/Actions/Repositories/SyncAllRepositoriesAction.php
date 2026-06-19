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

class SyncAllRepositoriesAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/resource.actions.sync.label'));

        $this->icon('phosphor-arrows-clockwise');

        $this->authorize(fn (): bool => Gate::allows('manage', Repository::class));

        $this->successNotificationTitle(__('repositories/resource.actions.sync.success'));

        $this->action(function () {
            $username = config('services.github.username');

            /** @var \App\Models\User $user */
            $user = Filament::auth()->user()->withoutRelations();

            Bus::batch([
                // We want these to run in a specific order.
                [
                    new ImportRepositoriesJob($username),
                    new ImportPackagistDownloadsJob($username),
                    new ImportNpmDownloadsJob,
                ],
            ])
                ->finally(function (Batch $batch) use ($user) {
                    $user->notify(new ManualRepositorySyncFinishedNotification($batch->id, $batch->name));
                })
                ->name('manual_repo_sync:all')
                ->dispatch();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'sync';
    }
}
