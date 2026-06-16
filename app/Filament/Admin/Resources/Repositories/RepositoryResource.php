<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Repositories;

use App\Filament\Admin\Resources\Repositories\Pages\ListRepositories;
use App\Filament\Admin\Resources\Repositories\Pages\ViewRepository;
use App\Filament\Schemas\Forms\Repositories\RepositoryForm;
use App\Filament\Schemas\Tables\Repositories\RepositoriesTable;
use App\Models\Repository;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RepositoryResource extends Resource
{
    protected static ?string $model = Repository::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-git-branch-duotone';

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

    public static function form(Schema $schema): Schema
    {
        return RepositoryForm::make($schema);
    }

    public static function table(Table $table): Table
    {
        return RepositoriesTable::make($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRepositories::route('/'),
            'view' => ViewRepository::route('/{record}'),
        ];
    }
}
