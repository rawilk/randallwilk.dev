<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Actions\Users\CreateUserAction;
use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Section::make('User info')
                    ->schema([
                        UserResource::getAvatarField(),
                        UserResource::getNameField()
                            ->hintAction(
                                Forms\Components\Actions\Action::make('fill')
                                    ->label('Fill form')
                                    ->color('warning')
                                    ->hidden(app()->isProduction())
                                    ->action(function () {
                                        $this->form->fill([
                                            'name' => 'Dexter Morgan',
                                            'email' => Str::random() . '@example.test',
                                            'timezone' => 'America/Chicago',
                                        ]);
                                    })
                            ),

                        UserResource::getEmailField()
                            ->unique(
                                table: User::class,
                            ),
                        UserResource::getTimezoneField(),
                        UserResource::getIsAdminField(),
                    ]),
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $user = app(CreateUserAction::class)($data);
        } catch (Throwable) {
            Notification::make()
                ->danger()
                ->title(__('users/create.exceptions.creation_failed'))
                ->send();

            $this->revertAvatarUpload(data_get($data, 'avatar_path'));

            throw new Halt;
        }

        return $user;
    }

    protected function revertAvatarUpload(?string $filename): void
    {
        if (blank($filename)) {
            return;
        }

        $disk = Storage::disk('avatars');

        if ($disk->exists($filename)) {
            $disk->delete($filename);
        }
    }
}
