<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Enums\Disk;
use App\Models\User;
use App\Notifications\Users\WelcomeNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

readonly class CreateUserAction
{
    protected const int RANDOM_PASSWORD_LENGTH = 16;

    public function __invoke(array $data): User
    {
        try {
            $user = User::create([
                ...Arr::except($data, 'password'),
                'password' => Str::password(static::RANDOM_PASSWORD_LENGTH),
            ]);

            $this->sendWelcomeEmail($user);
        } catch (Throwable $exception) {
            $this->revertAvatarUpload(data_get($data, 'avatar_path'));

            throw $exception;
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

    protected function sendWelcomeEmail(User $user): void
    {
        $user->notify(
            new WelcomeNotification(panelId: filament()->getId()),
        );
    }
}
