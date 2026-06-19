<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories;

use App\Jobs\Docs\CleanupDocsImportJob;
use App\Jobs\Docs\CleanupRepositoryFoldersJob;
use App\Jobs\Docs\RefreshDocsCacheJob;
use App\Jobs\Repositories\ImportDocsFromRepositoryJob;
use App\Models\Repository;
use App\Notifications\Repositories\ManualDocsImportFinishedNotification;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;

class ImportAllDocsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/resource.actions.import_docs.label'));

        $this->successNotificationTitle(__('repositories/resource.actions.import_docs.success'));

        $this->authorize(fn (): bool => Gate::allows('manage', Repository::class));

        $this->icon(Heroicon::ArrowUpTray);

        $this->action(function () {
            /** @var \App\Models\User $user */
            $user = Filament::auth()->user()->withoutRelations();

            $jobs = collect(config('docs.repositories'))
                ->keyBy('repository')
                ->map(fn (array $repository) => new ImportDocsFromRepositoryJob($repository))
                ->all();

            Bus::batch([
                [
                    new CleanupRepositoryFoldersJob,
                    ...$jobs,
                    new RefreshDocsCacheJob,
                ],
            ])
                ->finally(function (Batch $batch) use ($user) {
                    CleanupDocsImportJob::dispatch();

                    $user->notify(new ManualDocsImportFinishedNotification($batch->id, $batch->name));
                })
                ->name('manual_docs_import:all')
                ->dispatch();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'importDocs';
    }
}
