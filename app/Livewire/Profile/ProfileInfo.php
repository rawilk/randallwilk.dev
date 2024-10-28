<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Filament\Admin\Resources\UserResource;
use App\Filament\Infolists\AvatarFormEntry;
use Filament\Forms\Components\Component;
use Filament\Infolists;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Rawilk\ProfileFilament\Livewire\Profile\ProfileInfo as BaseProfileInfo;

class ProfileInfo extends BaseProfileInfo
{
    public function editAction(): Infolists\Components\Actions\Action
    {
        $action = parent::editAction();

        $action->modalWidth(MaxWidth::TwoExtraLarge);

        return $action;
    }

    protected function formSchema(): array
    {
        return [
            $this->nameInput(),
            UserResource::getTimezoneField(),
        ];
    }

    protected function infolistSchema(): array
    {
        return [
            Infolists\Components\Section::make(__('profile-filament::pages/profile.info.heading'))
                ->headerActions([
                    $this->editAction(),
                ])
                ->schema([
                    Infolists\Components\Split::make([
                        Infolists\Components\Group::make([
                            $this->nameTextEntry(),

                            Infolists\Components\TextEntry::make('timezone')
                                ->label(__('users/profile.timezone.label'))
                                ->formatStateUsing(
                                    fn (string $state): Htmlable => new HtmlString(Blade::render(<<<'HTML'
                                    <div>
                                        <div>{{ $timezone }}</div>
                                        <div class="italic text-xs">
                                            {{ __('users/profile.timezone.local_time', ['time' => $date->format('g:i a')]) }}
                                        </div>
                                    </div>
                                    HTML, ['date' => now()->tz($state), 'timezone' => $state]))
                                ),

                            $this->createdAtTextEntry(),
                        ])->grow(),

                        Infolists\Components\Group::make([
                            AvatarFormEntry::make('avatar_url'),
                        ])->grow(false),
                    ])->from('md'),
                ]),
        ];
    }

    protected function nameInput(): Component
    {
        return UserResource::getNameField()->placeholder(null);
    }
}
