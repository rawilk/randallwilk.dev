<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\RepositoryType;
use App\Filament\Admin\Actions\Repositories\DeleteRepositoryTableAction;
use App\Filament\Admin\Resources\RepositoryResource\Pages;
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
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use Livewire\Component;

class RepositoryResource extends Resource
{
    protected static ?string $model = Repository::class;

    protected static ?string $navigationIcon = 'phosphor-git-branch-duotone';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('repositories/resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('repositories/resource.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('repositories/resource.model.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    static::getTypeField(),
                ])->columns(['md' => 2])->columnSpanFull(),

                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('scoped_name')
                        ->label(__('repositories/resource.form.scoped_name.label'))
                        ->helperText(__('repositories/resource.form.scoped_name.help'))
                        ->hint(__('filament/forms.optional_field'))
                        ->placeholder(__('repositories/resource.form.scoped_name.placeholder'))
                        ->maxLength(255)
                        ->unique(
                            table: Repository::class,
                            ignoreRecord: true,
                        ),
                ])->columns(['md' => 2])->columnSpanFull(),

                Forms\Components\TextInput::make('documentation_url')
                    ->label(__('repositories/resource.form.documentation_url.label'))
                    ->placeholder(__('repositories/resource.form.documentation_url.placeholder'))
                    ->hint(__('filament/forms.optional_field'))
                    ->maxLength(255)
                    ->url(),

                Forms\Components\TextInput::make('blogpost_url')
                    ->label(__('repositories/resource.form.blogpost_url.label'))
                    ->placeholder(__('repositories/resource.form.blogpost_url.placeholder'))
                    ->hint(__('filament/forms.optional_field'))
                    ->maxLength(255)
                    ->url(),

                Forms\Components\Group::make([
                    static::getVisibleField(),

                    Forms\Components\Checkbox::make('highlighted')
                        ->label(__('repositories/resource.form.highlighted.label'))
                        ->helperText(__('repositories/resource.form.highlighted.help')),

                    Forms\Components\Checkbox::make('new')
                        ->label(__('repositories/resource.form.new.label'))
                        ->helperText(__('repositories/resource.form.new.help')),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('h_key')
                    ->label(__('filament/models.h_key.label'))
                    ->sortable()
                    ->copyable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('repositories/resource.table.name.label'))
                    ->sortable()
                    ->searchable(['name', 'scoped_name'])
                    ->description(fn (Repository $record): ?string => $record->scoped_name)
                    ->toggleable(false)
                    ->formatStateUsing(
                        fn (Repository $record): Htmlable => new HtmlString(Blade::render(<<<'HTML'
                        <div class="flex items-center gap-x-2">
                            <span>{{ $record->name }}</span>

                            <x-filament::badge
                                :color="$record->visible ? 'success' : 'danger'"
                            >
                                {{
                                    $record->visible
                                        ? __('repositories/view.attributes.visible.true')
                                        : __('repositories/view.attributes.visible.false')
                                }}
                            </x-filament::badge>
                        </div>
                        HTML, [
                            'record' => $record,
                        ]))
                    ),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('repositories/resource.table.type.label'))
                    ->sortable()
                    ->placeholder('-')
                    ->badge(),

                Tables\Columns\TextColumn::make('downloads')
                    ->label(__('repositories/resource.table.downloads.label'))
                    ->sortable()
                    ->formatStateUsing(fn (int $state) => Number::abbreviate($state, maxPrecision: 2)),

                Tables\Columns\TextColumn::make('stars')
                    ->label(__('repositories/resource.table.stars.label'))
                    ->sortable()
                    ->formatStateUsing(fn (int $state) => Number::abbreviate($state, maxPrecision: 2))
                    ->toggledHiddenByDefault(),

                Tables\Columns\CheckboxColumn::make('visible')
                    ->label(__('repositories/resource.table.visible.label'))
                    ->disabled(fn (Component $livewire): bool => ! $livewire->canManage)
                    ->sortable(),

                Tables\Columns\IconColumn::make('documentation_url')
                    ->label(__('repositories/resource.table.documentation_url.label'))
                    ->state(fn (Repository $record): bool => filled($record->documentation_url))
                    ->boolean()
                    ->sortable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\IconColumn::make('blogpost_url')
                    ->label(__('repositories/resource.table.blogpost_url.label'))
                    ->state(fn (Repository $record): bool => filled($record->blogpost_url))
                    ->boolean()
                    ->sortable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\CheckboxColumn::make('new')
                    ->label(__('repositories/resource.table.new.label'))
                    ->sortable()
                    ->disabled(fn (Component $livewire): bool => ! $livewire->canManage)
                    ->toggledHiddenByDefault(),

                Tables\Columns\CheckboxColumn::make('highlighted')
                    ->label(__('repositories/resource.table.highlighted.label'))
                    ->sortable()
                    ->disabled(fn (Component $livewire): bool => ! $livewire->canManage)
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('repositories/resource.table.type.label'))
                    ->options(RepositoryType::class)
                    ->native(false),

                Tables\Filters\TernaryFilter::make('has_type')
                    ->label(__('repositories/resource.table.filters.has_type.label'))
                    ->attribute('type')
                    ->nullable(),

                Tables\Filters\TernaryFilter::make('visible')
                    ->label(__('repositories/resource.table.visible.label')),

                Tables\Filters\TernaryFilter::make('new')
                    ->label(__('repositories/resource.table.new.label')),

                Tables\Filters\TernaryFilter::make('highlighted')
                    ->label(__('repositories/resource.table.highlighted.label')),

                Tables\Filters\TernaryFilter::make('documentation_url')
                    ->label(__('repositories/resource.table.documentation_url.label'))
                    ->nullable(),

                Tables\Filters\TernaryFilter::make('blogpost_url')
                    ->label(__('repositories/resource.table.blogpost_url.label'))
                    ->nullable(),

                Tables\Filters\QueryBuilder::make()
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('downloads')
                            ->label(__('repositories/resource.table.downloads.label'))
                            ->icon('heroicon-m-arrow-down-tray'),

                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('stars')
                            ->label(__('repositories/resource.table.stars.label'))
                            ->icon('heroicon-m-star'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\ActionGroup::make([
                        DeleteRepositoryTableAction::make(),
                        Tables\Actions\RestoreAction::make()
                            ->modalIconColor('primary')
                            ->modalSubmitActionLabel(__('filament-actions::restore.single.modal.actions.restore.label')),
                    ])->dropdown(false),
                ]),
            ])
            ->recordClasses(
                fn (Repository $record): array => [
                    'tbl-inactive' => ! $record->visible,
                ],
            )
            ->headerActions([
                Tables\Actions\ActionGroup::make([
                    static::getSyncAction(),
                    static::getImportDocsAction(),
                ])->tableHeader(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    static::getEditBulkAction(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->modalSubmitActionLabel(__('filament-actions::delete.multiple.modal.actions.delete.label'))
                        ->visible(function (Component $livewire): bool {
                            return (bool) rescue(
                                fn (): bool => $livewire->activeTab === 'not-trashed',
                                fn (): bool => true,
                            );
                        }),
                    Tables\Actions\RestoreBulkAction::make()
                        ->modalSubmitActionLabel(__('filament-actions::restore.multiple.modal.actions.restore.label'))
                        ->modalIconColor('primary')
                        ->visible(function (Component $livewire): bool {
                            return (bool) rescue(
                                fn (): bool => $livewire->activeTab === 'trashed',
                                fn (): bool => true,
                            );
                        }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepositories::route('/'),
            'view' => Pages\ViewRepository::route('/{record}'),
        ];
    }

    protected static function getSyncAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('sync')
            ->label(__('repositories/resource.actions.sync.label'))
            ->icon('phosphor-arrows-clockwise')
            ->authorize(fn (): bool => Gate::allows('manage', Repository::class))
            ->successNotificationTitle(__('repositories/resource.actions.sync.success'))
            ->action(function (Tables\Actions\Action $action) {
                $username = config('services.github.username');
                $user = auth()->user()->withoutRelations();

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

                $action->success();
            });
    }

    protected static function getImportDocsAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('importDocs')
            ->label(__('repositories/resource.actions.import_docs.label'))
            ->successNotificationTitle(__('repositories/resource.actions.import_docs.success'))
            ->authorize(fn (): bool => Gate::allows('manage', Repository::class))
            ->icon('heroicon-m-arrow-up-tray')
            ->action(function (Tables\Actions\Action $action) {
                $user = auth()->user()->withoutRelations();

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

                $action->success();
            });
    }

    protected static function getEditBulkAction(): Tables\Actions\BulkAction
    {
        return Tables\Actions\BulkAction::make('editBulk')
            ->form([
                static::bulkEditFieldGroup(
                    static::getTypeField(),
                ),

                static::bulkEditFieldGroup(
                    static::getVisibleField(),
                ),
            ])
            ->label(__('repositories/resource.actions.edit_bulk.label'))
            ->modalHeading(__('repositories/resource.actions.edit_bulk.modal_heading'))
            ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil-square')
            ->modalSubmitActionLabel(__('filament-actions::edit.single.modal.actions.save.label'))
            ->successNotificationTitle(__('filament-actions::edit.single.notifications.saved.title'))
            ->modalWidth(MaxWidth::SevenExtraLarge)
            ->authorize(fn (): bool => Gate::allows('manage', Repository::class))
            ->deselectRecordsAfterCompletion()
            ->action(function (Collection $records, Form $form, Tables\Actions\BulkAction $action) {
                $data = $form->getState(shouldCallHooksBefore: false);

                Repository::query()
                    ->withTrashed()
                    ->whereKey($records->pluck('id'))
                    ->update($data);

                $action->success();
            });
    }

    protected static function getTypeField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('type')
            ->label(__('repositories/resource.form.type.label'))
            ->options(RepositoryType::class)
            ->native(false)
            ->selectablePlaceholder(false)
            ->required()
            ->enum(RepositoryType::class);
    }

    protected static function getVisibleField(): Forms\Components\Checkbox
    {
        return Forms\Components\Checkbox::make('visible')
            ->label(__('repositories/resource.form.visible.label'))
            ->helperText(__('repositories/resource.form.visible.help'));
    }

    protected static function bulkEditFieldGroup(Forms\Components\Field $field): Forms\Components\Group
    {
        return Forms\Components\Group::make([
            Forms\Components\Select::make("{$field->getName()}_status")
                ->label($field->getLabel())
                ->default('keep')
                ->options([
                    'keep' => __('filament/forms.bulk_edit.field_status.options.keep'),
                    'edit' => __('filament/forms.bulk_edit.field_status.options.edit'),
                ])
                ->selectablePlaceholder(false)
                ->dehydrated(false)
                ->live(),

            $field
                ->label(__('filament/forms.bulk_edit.new_value.label'))
                ->visible(fn (Forms\Get $get): bool => $get("{$field->getName()}_status") === 'edit'),
        ])
            ->columns(2);
    }
}
