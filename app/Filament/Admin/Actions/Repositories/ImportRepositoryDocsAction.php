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

class ImportRepositoryDocsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/view.actions.import_docs.label'));

        $this->icon(Heroicon::ArrowUpTray);

        $this->authorize(fn (Repository $record): bool => Gate::allows('importDocs', $record));

        $this->successNotificationTitle(__('repositories/view.actions.import_docs.success'));

        $this->action(function (Repository $record) {
            $config = $this->getRepositoryConfig($record);

            /** @var \App\Models\User $user */
            $user = Filament::auth()->user()->withoutRelations();

            Bus::batch([
                [
                    new CleanupRepositoryFoldersJob,
                    new ImportDocsFromRepositoryJob($config),
                    new RefreshDocsCacheJob,
                ],
            ])
                ->finally(function (Batch $batch) use ($user, $record) {
                    CleanupDocsImportJob::dispatch();

                    $user->notify(
                        new ManualDocsImportFinishedNotification(
                            $batch->id,
                            $batch->name,
                            $record,
                        ),
                    );
                })
                ->name("manual_doc_import:{$record->full_name}")
                ->dispatch();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'importDocs';
    }

    protected function getRepositoryConfig(Repository $record): ?array
    {
        $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

        return data_get($repositoriesWithDocs, $record->full_name);
    }
}
