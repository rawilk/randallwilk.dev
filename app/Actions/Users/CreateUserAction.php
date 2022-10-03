<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\LaravelBase\PasswordValidationRules;
use App\Models\User\User;
use App\Notifications\Users\WelcomeNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Rawilk\LaravelCasters\Support\Name;

final class CreateUserAction
{
    use PasswordValidationRules;

    /** @var int */
    private const RANDOM_PASSWORD_LENGTH = 16;

    public function __invoke(array $input, ?User $authenticatedUser = null, bool $skipPermissionsValidation = false): void
    {
        $data = convertEmptyStringsToNull(
            $this->validate($input)
        );

        $newPassword = $data['password'] ?? $this->generateRandomPassword();

        $user = tap(User::make(), function (User $user) use ($data, $newPassword) {
            $name = Name::from($data['name']);

            $user->forceFill([
                'first_name' => $name->first,
                'last_name' => $name->last,
                'email' => $data['email'],
                'timezone' => $data['timezone'],
                'password' => $newPassword,
            ])->save();
        });

        if (isset($data['photo'])) {
            $user->updateAvatar($data['photo']);
        }

        $this->setAbilities($user, [
            'permissions' => $input['permissions'] ?? null,
            'roles' => $input['roles'] ?? null,
            $authenticatedUser,
            $skipPermissionsValidation,
        ]);

        $this->sendWelcomeEmail($user, $newPassword);
    }

    private function validate(array $input): array
    {
        return Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users'),
            ],
            'password' => $this->passwordRules(true, false),
            'timezone' => ['required', 'string'],
            'photo' => [
                'nullable',
                File::image()->max(1024),
            ],
        ])->validate();
    }

    private function setAbilities(User $user, array $input, ?User $authenticatedUser = null, bool $skipPermissionsValidation = false): void
    {
        /*
         * A default role will be assigned in the action if the authenticated
         * user is not allowed to assign roles.
         */
        app(UpdateAbilitiesAction::class)($user, $input, $authenticatedUser, $skipPermissionsValidation);
    }

    private function generateRandomPassword(): string
    {
        return Str::random(self::RANDOM_PASSWORD_LENGTH);
    }

    private function sendWelcomeEmail(User $user, string $password): void
    {
        $user->notify(new WelcomeNotification($password));
    }
}
