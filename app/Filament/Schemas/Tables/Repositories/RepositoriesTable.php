<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Tables\Repositories;

use App\Enums\RepositoryType;
use App\Filament\Admin\Actions\Repositories\BulkEditRepositoriesAction;
use App\Filament\Admin\Actions\Repositories\DeleteRepositoryAction;
use App\Filament\Admin\Actions\Repositories\ImportAllDocsAction;
use App\Filament\Admin\Actions\Repositories\SyncAllRepositoriesAction;
use App\Models\Repository;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use Livewire\Component;

class RepositoriesTable
{
    public static function make(Table $table): Table
    {
        $canManage = Gate::allows('manage', Repository::class);

        return $table
            ->defaultSort('name')
            ->columns(static::columns($canManage))
            ->filters(static::filters())
            ->recordActions(static::recordActions())
            ->recordClasses(
                fn (Repository $record): array => [
                    'tbl-inactive' => ! $record->visible,
                ],
            )
            ->headerActions(static::headerActions())
            ->toolbarActions(static::toolbarActions());
    }

    protected static function columns(bool $canManage): array
    {
        return [
            TextColumn::make('h_key')
                ->label(__('filament/models.h_key.label'))
                ->sortable()
                ->fontFamily(FontFamily::Mono)
                ->copyable()
                ->toggledHiddenByDefault(),

            TextColumn::make('name')
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
                    ])),
                ),

            TextColumn::make('type')
                ->label(__('repositories/resource.table.type.label'))
                ->sortable()
                ->placeholder('-')
                ->badge(),

            TextColumn::make('downloads')
                ->label(__('repositories/resource.table.downloads.label'))
                ->sortable()
                ->formatStateUsing(fn (int $state) => Number::abbreviate($state, maxPrecision: 2)),

            TextColumn::make('stars')
                ->label(__('repositories/resource.table.stars.label'))
                ->sortable()
                ->formatStateUsing(fn (int $state) => Number::abbreviate($state, maxPrecision: 2))
                ->toggledHiddenByDefault(),

            CheckboxColumn::make('visible')
                ->label(__('repositories/resource.table.visible.label'))
                ->disabled(fn (): bool => ! $canManage)
                ->sortable(),

            IconColumn::make('documentation_url')
                ->label(__('repositories/resource.table.documentation_url.label'))
                ->state(fn (Repository $record): bool => filled($record->documentation_url))
                ->boolean()
                ->sortable()
                ->toggledHiddenByDefault(),

            IconColumn::make('blogpost_url')
                ->label(__('repositories/resource.table.blogpost_url.label'))
                ->state(fn (Repository $record): bool => filled($record->blogpost_url))
                ->boolean()
                ->sortable()
                ->toggledHiddenByDefault(),

            CheckboxColumn::make('new')
                ->label(__('repositories/resource.table.new.label'))
                ->sortable()
                ->disabled(fn (Component $livewire): bool => ! $canManage)
                ->toggledHiddenByDefault(),

            CheckboxColumn::make('highlighted')
                ->label(__('repositories/resource.table.highlighted.label'))
                ->sortable()
                ->disabled(fn (Component $livewire): bool => ! $canManage)
                ->toggledHiddenByDefault(),
        ];
    }

    protected static function filters(): array
    {
        return [
            SelectFilter::make('type')
                ->label(__('repositories/resource.table.type.label'))
                ->options(RepositoryType::class)
                ->native(false),

            TernaryFilter::make('has_type')
                ->label(__('repositories/resource.table.filters.has_type.label'))
                ->attribute('type')
                ->nullable(),

            TernaryFilter::make('visible')
                ->label(__('repositories/resource.table.visible.label')),

            TernaryFilter::make('new')
                ->label(__('repositories/resource.table.new.label')),

            TernaryFilter::make('highlighted')
                ->label(__('repositories/resource.table.highlighted.label')),

            TernaryFilter::make('documentation_url')
                ->label(__('repositories/resource.table.documentation_url.label'))
                ->nullable(),

            TernaryFilter::make('blogpost_url')
                ->label(__('repositories/resource.table.blogpost_url.label'))
                ->nullable(),

            QueryBuilder::make()
                ->constraints([
                    NumberConstraint::make('downloads')
                        ->label(__('repositories/resource.table.downloads.label'))
                        ->icon('heroicon-m-arrow-down-tray'),

                    NumberConstraint::make('stars')
                        ->label(__('repositories/resource.table.stars.label'))
                        ->icon('heroicon-m-star'),
                ]),
        ];
    }

    protected static function recordActions(): array
    {
        return [
            ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),

                ActionGroup::make([
                    DeleteRepositoryAction::make(),

                    RestoreAction::make()
                        ->modalIconColor('primary'),
                ])->dropdown(false),
            ]),
        ];
    }

    protected static function headerActions(): array
    {
        return [
            ActionGroup::make([
                SyncAllRepositoriesAction::make(),
                ImportAllDocsAction::make(),
            ])->tableHeader(),
        ];
    }

    protected static function toolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                BulkEditRepositoriesAction::make(),

                DeleteBulkAction::make()
                    ->visible(fn (Component $livewire): bool => $livewire->activeTab === 'not-trashed'),

                RestoreBulkAction::make()
                    ->visible(fn (Component $livewire): bool => $livewire->activeTab === 'trashed')
                    ->modalIconColor('primary'),
            ]),
        ];
    }
}
