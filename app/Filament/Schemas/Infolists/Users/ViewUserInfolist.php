<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Infolists\Users;

use App\Filament\Admin\Actions\Users\DeleteUserAction;
use App\Filament\Schemas\CopyableText;
use App\Filament\Schemas\Infolists\DateEntry;
use App\Livewire\Users\UpdateUserInfoForm;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ViewUserInfolist
{
    public static function make(): array
    {
        return [
            // User info
            Section::make(__('users/view.sections.user_info.heading'))
                ->collapsible()
                ->schema(fn (User $record): array => [
                    Livewire::make(UpdateUserInfoForm::class, [
                        'user' => $record,
                    ])
                        ->key('updateUserInfoForm'),
                ]),

            //  User meta
            Section::make(__('users/view.sections.meta.heading'))
                ->collapsible()
                ->inlineLabel()
                ->schema([
                    CopyableText::make('id')
                        ->label(__('users/view.attributes.id.label'))
                        ->fontMono(),

                    CopyableText::make('h_key')
                        ->label(__('users/view.attributes.h_key.label'))
                        ->fontMono(),

                    DateEntry::make('created_at')
                        ->label(__('filament/models.created_at.label')),

                    DateEntry::make('updated_at')
                        ->label(__('filament/models.updated_at.label')),
                ]),

            // User actions
            Section::make(__('users/view.sections.actions.heading'))
                ->collapsible()
                ->hidden(fn (User $record): bool => $record->is(Filament::auth()->user()))
                ->schema(fn (User $record): array => [
                    Flex::make([
                        Group::make([
                            Text::make(new HtmlString(Blade::render(<<<'HTML'
                            <div class="font-semibold">{{ __('users/resource.actions.delete.infolist_title') }}</div>
                            <div>
                                {{ __('users/resource.actions.delete.infolist_description', ['first_name' => $record->name->first_possessive]) }}
                            </div>
                            HTML, [
                                'record' => $record,
                            ]))),
                        ]),

                        Actions::make([
                            DeleteUserAction::make(),
                        ])->grow(false),
                    ])->from('md'),
                ]),
        ];
    }
}
