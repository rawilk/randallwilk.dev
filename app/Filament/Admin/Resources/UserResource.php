<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Actions\Users\DeleteUserAction;
use App\Filament\Admin\Actions\Users\DeleteUserTableAction;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Tables\Columns\DateColumn;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Rawilk\LaravelCasters\Support\Name;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;

use function App\Helpers\appTimezone;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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

                static::getNameColumn(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('users/resource.table.email.label'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('timezone')
                    ->label(__('users/resource.table.timezone.label'))
                    ->sortable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\ToggleColumn::make('is_admin')
                    ->label(__('users/resource.table.is_admin.label'))
                    ->sortable()
                    ->disabled(fn (User $record): bool => $record->is(auth()->user()) || Gate::denies('update', $record)),

                DateColumn::make('created_at')
                    ->label(__('filament/models.created_at.label'))
                    ->sortable()
                    ->toggledHiddenByDefault(),

                DateColumn::make('updated_at')
                    ->label(__('filament/models.updated_at.label'))
                    ->sortable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label(__('users/resource.table.is_admin.label')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make(static::userActions()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    static::getDeleteBulkAction(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function getNameField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('users/resource.form.name.label'))
            ->placeholder(__('users/resource.form.name.placeholder'))
            ->required()
            ->maxLength(255)
            ->afterStateHydrated(function (Forms\Components\TextInput $component, null|string|Name $state) {
                if ($state instanceof Name) {
                    $component->state($state->full);
                }
            });
    }

    public static function getEmailField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('email')
            ->label(__('users/resource.form.email.label'))
            ->placeholder(__('users/resource.form.email.placeholder'))
            ->email()
            ->required()
            ->maxLength(255);
    }

    public static function getTimezoneField(): TimezoneSelect
    {
        return TimezoneSelect::make('timezone')
            ->label(__('users/resource.form.timezone.label'))
            ->searchable()
            ->optionsLimit(100)
            ->selectablePlaceholder(false)
            ->default(fn () => appTimezone())
            ->required();
    }

    public static function getIsAdminField(): Forms\Components\Checkbox
    {
        return Forms\Components\Checkbox::make('is_admin')
            ->label(__('users/resource.form.is_admin.label'))
            ->helperText(__('users/resource.form.is_admin.help'));
    }

    public static function getAvatarField(): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make('avatar_path')
            ->label(__('users/resource.form.avatar.label'))
            ->helperText(__('filament/forms.optional_field'))
            ->disk('avatars')
            ->avatar()
            ->image()
            ->imageEditor()
            ->circleCropper()
            ->maxSize(1024);
    }

    protected static function getNameColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('name')
            ->label(__('users/resource.table.name.label'))
            ->searchable()
            ->sortable()
            ->toggleable(false)
            ->formatStateUsing(function (User $record) {
                return new HtmlString(Blade::render(<<<'HTML'
                <div class="flex items-center gap-x-1.5">
                    <div>
                        <img
                            src="{{ $user->avatar_url }}"
                            class="max-w-none object-cover object-center rounded-full ring-white dark:ring-gray-900 w-8 h-8"
                        />
                    </div>

                    <span>{{ $user->name->full }}</span>
                </div>
                HTML, ['user' => $record]));
            });
    }

    protected static function userActions(): array
    {
        return [
            Tables\Actions\EditAction::make()->url(fn (User $record): string => static::getUrl('view', ['record' => $record])),
            DeleteUserTableAction::make(),
        ];
    }

    protected static function getDeleteBulkAction(): Tables\Actions\DeleteBulkAction
    {
        return Tables\Actions\DeleteBulkAction::make()
            ->modalDescription(
                fn (): Htmlable => new HtmlString(Blade::render(<<<'HTML'
                <x-filament.modal-description align="center" :pretty-text="false" balance-text>
                    {{ str(__('users/resource.actions.delete_bulk.modal_description'))->markdown()->toHtmlString() }}
                </x-filament.modal-description>
                HTML)),
            )
            ->modalSubmitActionLabel(__('filament-actions::delete.multiple.modal.actions.delete.label'))
            ->using(function (Collection $records, DeleteUserAction $deleter) {
                $records
                    ->filter(fn (User $user): bool => Gate::allows('delete', $user))
                    ->each(function (User $user) use ($deleter) {
                        $deleter($user);
                    });
            });
    }
}
