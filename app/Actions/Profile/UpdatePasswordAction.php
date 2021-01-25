<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class UpdatePasswordAction
{
    use PasswordValidationRules;

    public function execute(User $user, array $input, bool $updatingOtherUser = false): void
    {
        $needsConfirm = ! $updatingOtherUser;
        $currentPassword = $updatingOtherUser
            ? Auth::user()->password
            : $user->password;

        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules($needsConfirm),
        ])->after(function ($validator) use ($input, $currentPassword) {
            if (! Hash::check($input['current_password'], $currentPassword)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
