<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Actions\Users\CreateUserAction;
use App\Enums\Disk;
use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $user = app(CreateUserAction::class)($data);
        } catch (Throwable $e) {
            Notification::make()
                ->danger()
                ->title(__('users/create.exceptions.creation_failed'))
                ->send();

            $this->revertAvatarUpload(data_get($data, 'avatar_path'));

            report($e);

            throw new Halt;
        }

        return $user;
    }

    protected function revertAvatarUpload(?string $filename): void
    {
        if (blank($filename)) {
            return;
        }

        $disk = Disk::Avatars->toStorageDisk();

        if ($disk->exists($filename)) {
            $disk->delete($filename);
        }
    }
}
