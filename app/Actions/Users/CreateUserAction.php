<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use App\Notifications\Users\WelcomeNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CreateUserAction
{
    protected const int RANDOM_PASSWORD_LENGTH = 16;

    public function __invoke(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                ...$data,
                'password' => Str::password(static::RANDOM_PASSWORD_LENGTH),
            ]);

            $this->sendWelcomeEmail($user);
        } catch (Throwable $exception) {
            DB::rollBack();

            $this->revertAvatarUpload(data_get($data, 'avatar_path'));

            throw $exception;
        }

        DB::commit();

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

    protected function sendWelcomeEmail(User $user): void
    {
        $user->notify(
            new WelcomeNotification(panelId: 'admin'),
        );
    }
}
