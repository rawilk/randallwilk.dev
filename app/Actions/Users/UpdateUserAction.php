<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\LaravelBase\PasswordValidationRules;
use App\Actions\LaravelBase\UpdateUserProfileInformationAction;
use App\Events\Users\UserPasswordWasUpdatedEvent;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class UpdateUserAction
{
    use PasswordValidationRules;

    public function __invoke(User $user, array $input, User $authenticatedUser = null): void
    {
        if (! $this->shouldEdit($user, $authenticatedUser)) {
            return;
        }

        app(UpdateUserProfileInformationAction::class)->update($user, $input);

        app(UpdateAbilitiesAction::class)($user, $input, $authenticatedUser, true);

        $password = $input['password'] ?? null;
        if (! empty($password)) {
            $this->updateUserPassword($user, $password);
        }
    }

    private function updateUserPassword(User $user, string $newPassword): void
    {
        // Only process this change if the user's password actually changes.
        if (Hash::check($newPassword, $user->password)) {
            return;
        }

        $validator = Validator::make(['password' => $newPassword], [
            'password' => $this->passwordRules(needsConfirm: false),
        ]);

        if ($validator->fails()) {
            return;
        }

        $user->forceFill([
            'password' => $newPassword,
        ])->save();

        event(new UserPasswordWasUpdatedEvent($user, false));
    }

    private function shouldEdit(User $user, ?User $authenticatedUser): bool
    {
        $authenticatedUser = $authenticatedUser ?: Auth::user();

        if (! $authenticatedUser) {
            return true;
        }

        return $authenticatedUser->can('edit', $user);
    }
}
