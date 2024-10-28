<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\RepositoryResource\Pages;

use App\Filament\Admin\Actions\Repositories\DeleteRepositoryAction;
use App\Filament\Admin\Resources\RepositoryResource;
use App\Filament\Infolists\CopyableTextEntry;
use App\Filament\Infolists\DateEntry;
use App\Jobs\Docs\CleanupDocsImportJob;
use App\Jobs\Docs\CleanupRepositoryFoldersJob;
use App\Jobs\Docs\RefreshDocsCacheJob;
use App\Jobs\Repositories\ImportDocsFromRepositoryJob;
use App\Jobs\Repositories\ImportNpmDownloadsJob;
use App\Jobs\Repositories\ImportPackagistDownloadsJob;
use App\Jobs\Repositories\ImportRepositoriesJob;
use App\Models\Repository;
use App\Notifications\Repositories\ManualDocsImportFinishedNotification;
use App\Notifications\Repositories\ManualRepositorySyncFinishedNotification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

use function App\Helpers\formatPageTitle;

class ViewRepository extends ViewRecord
{
    protected static string $resource = RepositoryResource::class;

    public function getHeading(): string|Htmlable
    {
        return new HtmlString(Blade::render(<<<'HTML'
        <div class="flex items-center gap-x-3">
            <span>{{ $record->name }}</span>

            <x-filament::badge
                :color="$record->visible ? 'success' : 'danger'"
                class="mt-1"
            >
                {{
                    $record->visible
                        ? __('repositories/view.attributes.visible.true')
                        : __('repositories/view.attributes.visible.false')
                }}
            </x-filament::badge>
        </div>
        HTML, [
            'record' => $this->getRecord(),
        ]));
    }

    public function getTitle(): string|Htmlable
    {
        return formatPageTitle(
            $this->getRecord()->name,
            static::getResource()::getBreadcrumb(),
        );
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                $this->getInfoSection(),
                $this->getGitHubMetaSection(),
                $this->getMetaSection(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                EditAction::make(),

                ActionGroup::make([
                    $this->getSyncAction(),
                    $this->getImportDocsAction(),
                ])->dropdown(false),

                ActionGroup::make([
                    DeleteRepositoryAction::make(),
                    RestoreAction::make()
                        ->modalIconColor('primary')
                        ->modalSubmitActionLabel(__('filament-actions::restore.single.modal.actions.restore.label')),
                ])->dropdown(false),
            ])->pageHeader(),
        ];
    }

    protected function getInfoSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make(__('repositories/view.sections.info.heading'))
            ->schema([
                Infolists\Components\Group::make([
                    Infolists\Components\TextEntry::make('name')
                        ->label(__('repositories/view.attributes.name.label'))
                        ->url(
                            fn (Repository $record): string => $record->url,
                            shouldOpenInNewTab: true,
                        ),

                    Infolists\Components\TextEntry::make('scoped_name')
                        ->label(__('repositories/view.attributes.scoped_name.label'))
                        ->placeholder(__('repositories/view.attributes.scoped_name.placeholder'))
                        ->helperText(__('repositories/view.attributes.scoped_name.help')),
                ])->columns(['md' => 2]),

                Infolists\Components\TextEntry::make('description')
                    ->placeholder('-')
                    ->label(__('repositories/view.attributes.description.label')),

                Infolists\Components\TextEntry::make('type')
                    ->placeholder(__('repositories/view.attributes.type.placeholder'))
                    ->label(__('repositories/view.attributes.type.label'))
                    ->badge(),

                Infolists\Components\TextEntry::make('language')
                    ->label(__('repositories/view.attributes.language.label'))
                    ->badge(),

                Infolists\Components\TextEntry::make('documentation_url')
                    ->label(__('repositories/view.attributes.documentation_url.label'))
                    ->placeholder('-')
                    ->url(
                        fn (Repository $record): ?string => $record->documentation_url,
                        shouldOpenInNewTab: true,
                    ),

                Infolists\Components\TextEntry::make('blogpost_url')
                    ->label(__('repositories/view.attributes.blogpost_url.label'))
                    ->placeholder('-')
                    ->url(
                        fn (Repository $record): ?string => $record->blogpost_url,
                        shouldOpenInNewTab: true,
                    ),

                Infolists\Components\IconEntry::make('highlighted')
                    ->label(__('repositories/view.attributes.highlighted.label')),

                Infolists\Components\IconEntry::make('new')
                    ->label(__('repositories/view.attributes.new.label')),
            ]);
    }

    protected function getGitHubMetaSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make(__('repositories/view.sections.github_meta.heading'))
            ->collapsible()
            ->schema([
                DateEntry::make('repository_created_at')
                    ->label(__('repositories/view.attributes.repository_created_at.label'))
                    ->helperText(__('repositories/view.attributes.repository_created_at.help')),

                Infolists\Components\TextEntry::make('stars')
                    ->label(__('repositories/view.attributes.stars.label'))
                    ->numeric(),

                Infolists\Components\TextEntry::make('downloads')
                    ->label(__('repositories/view.attributes.downloads.label'))
                    ->numeric()
                    ->visible(fn (Repository $record): bool => $record->isPackage()),

                Infolists\Components\TextEntry::make('topics')
                    ->label(__('repositories/view.attributes.topics.label'))
                    ->placeholder('-')
                    ->badge(),
            ]);
    }

    protected function getMetaSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make(__('repositories/view.sections.meta.heading'))
            ->collapsible()
            ->inlineLabel()
            ->schema([
                CopyableTextEntry::make('id')
                    ->label(__('repositories/view.attributes.id.label'))
                    ->bordered(),

                CopyableTextEntry::make('h_key')
                    ->label(__('repositories/view.attributes.h_key.label'))
                    ->bordered(),

                DateEntry::make('created_at')
                    ->label(__('filament/models.created_at.label')),

                DateEntry::make('updated_at')
                    ->label(__('filament/models.updated_at.label')),
            ]);
    }

    protected function getSyncAction(): Action
    {
        return Action::make('sync')
            ->label(__('repositories/view.actions.sync.label'))
            ->icon('phosphor-arrows-clockwise')
            ->authorize(fn (): bool => Gate::allows('manage', Repository::class))
            ->successNotificationTitle(__('repositories/view.actions.sync.success'))
            ->action(function (Repository $record, Action $action) {
                $jobs = array_filter([
                    new ImportRepositoriesJob(config('services.github.username'), $record->name),
                    $this->getSyncImportsJob($record),
                ]);

                $user = auth()->user()->withoutRelations();

                Bus::batch([$jobs])
                    ->finally(function (Batch $batch) use ($user) {
                        $user->notify(
                            new ManualRepositorySyncFinishedNotification($batch->id, $batch->name)
                        );
                    })
                    ->name("manual_repo_sync:{$record->name}")
                    ->dispatch();

                $action->success();
            });
    }

    protected function getImportDocsAction(): Action
    {
        return Action::make('importDocs')
            ->label(__('repositories/view.actions.import_docs.label'))
            ->icon('heroicon-m-arrow-up-tray')
            ->authorize(fn (Repository $record): bool => Gate::allows('importDocs', $record))
            ->successNotificationTitle(__('repositories/view.actions.import_docs.success'))
            ->action(function (Repository $record, Action $action) {
                $config = $this->getRepositoryConfig($record);

                $user = auth()->user()->withoutRelations();

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
                            )
                        );
                    })
                    ->name("manual_doc_import:{$record->full_name}")
                    ->dispatch();

                $action->success();
            });
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

    protected function getRepositoryConfig(Repository $record): ?array
    {
        $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

        return data_get($repositoriesWithDocs, $record->full_name);
    }
}
