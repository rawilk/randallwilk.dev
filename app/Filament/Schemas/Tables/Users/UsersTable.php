<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Tables\Users;

use App\Filament\Admin\Actions\Users\DeleteUserAction;
use App\Filament\Admin\Actions\Users\DeleteUsersBulkAction;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Schemas\Tables\Columns\DateColumn;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class UsersTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('h_key')
                    ->label(__('filament/models.h_key.label'))
                    ->sortable()
                    ->copyable()
                    ->fontFamily(FontFamily::Mono)
                    ->toggledHiddenByDefault(),

                TextColumn::make('name')
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
                    }),

                TextColumn::make('email')
                    ->label(__('users/resource.table.email.label'))
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('timezone')
                    ->label(__('users/resource.table.timezone.label'))
                    ->sortable()
                    ->toggledHiddenByDefault(),

                ToggleColumn::make('is_admin')
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
                TernaryFilter::make('is_admin')
                    ->label(__('users/resource.table.is_admin.label')),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->url(fn (User $record): string => UserResource::getUrl('view', ['record' => $record])),

                    DeleteUserAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteUsersBulkAction::make(),
                ]),
            ]);
    }
}
