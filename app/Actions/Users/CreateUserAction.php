<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\PasswordValidationRules;
use App\Models\User;
use App\Notifications\Users\WelcomeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

final class CreateUserAction
{
    use PasswordValidationRules;

    /** @var int */
    private const RANDOM_PASSWORD_LENGTH = 16;

    public function execute(array $input): User
    {
        $data = $this->validate($input);

        $newPassword = $data['password'] ?? $this->generateRandomPassword();

        $user = tap(User::make(), static function (User $user) use ($data, $newPassword) {
            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'timezone' => $data['timezone'],
                'password' => $newPassword,
                'is_admin' => $data['is_admin'] ?? false,
            ])->save();
        });

        if (isset($data['photo'])) {
            $user->updateAvatar($data['photo']);
        }

        $this->sendWelcomeEmail($user, $newPassword);

        return $user;
    }

    private function validate(array $input): array
    {
        $rules = collect([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users'),
            ],
            'password' => $this->passwordRules(needsConfirm: false, optional: true),
            'timezone' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:1024'],
            'is_admin' => $this->isAdmin() ? ['sometimes', 'boolean'] : null,
        ])->filter()->toArray();

        return Validator::make($input, $rules)->validate();
    }

    private function generateRandomPassword(): string
    {
        return Str::random(self::RANDOM_PASSWORD_LENGTH);
    }

    private function sendWelcomeEmail(User $user, string $password): void
    {
        $user->notify(new WelcomeNotification($password));
    }

    private function isAdmin(): bool
    {
        return Auth::hasUser() && Auth::user()->is_admin;
    }
}
