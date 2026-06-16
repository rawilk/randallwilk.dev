<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Schemas\Forms\Users\UserInfoForm;
use App\Filament\Schemas\Tables\Users\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static bool $isGloballySearchable = false;

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('users/resource.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users/resource.model.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('users/resource.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components(UserInfoForm::make());
    }

    public static function table(Table $table): Table
    {
        return UsersTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}
