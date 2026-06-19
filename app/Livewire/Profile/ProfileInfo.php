<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Filament\Schemas\Forms\Users\AvatarFormEntry;
use App\Filament\Schemas\Forms\Users\NameInput;
use App\Filament\Schemas\Forms\Users\UserTimeZoneSelect;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Rawilk\ProfileFilament\Facades\ProfileFilament;
use Rawilk\ProfileFilament\Filament\Actions\EditProfileInfoAction;
use Rawilk\ProfileFilament\Livewire\Profile\ProfileInfo as BaseProfileInfo;

class ProfileInfo extends BaseProfileInfo
{
    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record(Filament::auth()->user())
            ->components([
                Section::make(__('profile-filament::pages/profile/page.info.heading'))
                    ->key('profile-information')
                    ->headerActions([
                        $this->editAction(),
                    ])
                    ->schema([
                        Flex::make([
                            Group::make([
                                TextEntry::make('name')
                                    ->label(__('profile-filament::pages/profile/page.info.fields.name.label')),

                                TextEntry::make('timezone')
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

                                TextEntry::make('created_at')
                                    ->label(__('profile-filament::pages/profile/page.info.fields.created-at.label'))
                                    ->dateTime(
                                        format: 'F j, Y',
                                        timezone: ProfileFilament::userTimezone(),
                                    ),
                            ]),

                            Group::make([
                                AvatarFormEntry::make('avatar_url'),
                            ])->grow(false),
                        ])->from('md'),
                    ]),
            ]);
    }

    protected function editAction(): EditProfileInfoAction
    {
        return EditProfileInfoAction::make()
            ->modalWidth(Width::TwoExtraLarge)
            ->fillForm(fn (): array => [
                'name' => Filament::auth()->user()->name,
                'timezone' => Filament::auth()->user()->timezone,
            ])
            ->schema([
                NameInput::make()->placeholder(null),
                UserTimeZoneSelect::make(),
            ])
            ->using(function (array $data) {
                /** @var \App\Models\User $user */
                $user = Filament::auth()->user();

                $user->fill([
                    'name' => $data['name']->full,
                    'timezone' => $data['timezone'],
                ])->save();

                return true;
            });
    }
}
