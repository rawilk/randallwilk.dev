<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Actions\Users\DeleteUserInfolistAction;
use App\Filament\Admin\Resources\UserResource;
use App\Filament\Infolists\ActionListItem;
use App\Filament\Infolists\CopyableTextEntry;
use App\Filament\Infolists\DateEntry;
use App\Livewire\Users\UpdatePasswordForm;
use App\Livewire\Users\UpdateUserInfoForm;
use App\Models\User;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

use function App\Helpers\formatPageTitle;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        FilamentView::registerRenderHook(
            name: PanelsRenderHook::CONTENT_END,
            hook: fn () => Blade::render("@livewire('sudo-challenge-form')"),
        );
    }

    public function getHeading(): string|Htmlable
    {
        return $this->getRecord()->name->full;
    }

    public function getTitle(): string|Htmlable
    {
        return formatPageTitle(
            $this->getRecord()->name->full,
            static::getResource()::getBreadcrumb(),
        );
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Infolists\Components\Section::make(__('users/view.sections.user_info.heading'))
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Livewire::make(UpdateUserInfoForm::class, fn (User $record) => ['user' => $record])
                            ->key('userInfoForm'),
                    ]),

                Infolists\Components\Section::make(__('users/view.sections.password.heading'))
                    ->description(__('users/view.sections.password.description'))
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Livewire::make(UpdatePasswordForm::class, fn (User $record) => ['user' => $record])
                            ->key('updatePasswordForm'),
                    ]),

                $this->metaSection(),
                $this->actionsSection(),
            ]);
    }

    protected function metaSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make(__('users/view.sections.meta.heading'))
            ->collapsible()
            ->inlineLabel()
            ->schema([
                CopyableTextEntry::make('id')
                    ->label(__('users/view.attributes.id.label'))
                    ->bordered(),

                CopyableTextEntry::make('h_key')
                    ->label(__('users/view.attributes.h_key.label'))
                    ->bordered(),

                DateEntry::make('created_at')
                    ->label(__('filament/models.created_at.label')),

                DateEntry::make('updated_at')
                    ->label(__('filament/models.updated_at.label')),
            ]);
    }

    protected function actionsSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make(__('users/view.sections.actions.heading'))
            ->collapsible()
            ->hidden(fn (User $record): bool => $record->is(auth()->user()))
            ->schema([
                ActionListItem::make('delete')
                    ->label(__('users/resource.actions.delete.infolist_title'))
                    ->description(fn (User $record): string => __('users/resource.actions.delete.infolist_description', ['first_name' => $record->name->first_possessive]))
                    ->visible(fn (User $record): bool => Gate::allows('delete', $record))
                    ->action(
                        DeleteUserInfolistAction::make()
                            ->successRedirectUrl(UserResource::getUrl())
                    ),
            ]);
    }
}
