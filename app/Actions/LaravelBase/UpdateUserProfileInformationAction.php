<?php

declare(strict_types=1);

namespace App\Actions\LaravelBase;

use App\Events\Users\UserProfileInformationUpdatedEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserProfileInformation;
use Rawilk\LaravelCasters\Support\Name;

final class UpdateUserProfileInformationAction implements UpdatesUserProfileInformation
{
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->getKey()),
            ],
            'timezone' => ['required', 'string'],
            'photo' => [
                'nullable',
                File::image()->max(1024),
            ],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateAvatar($input['photo']);
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);

            return;
        }

        $name = Name::from($input['name']);

        $user->forceFill([
            'first_name' => $name->first,
            'last_name' => $name->last,
            'email' => $input['email'],
            'timezone' => $input['timezone'],
        ]);

        event(new UserProfileInformationUpdatedEvent($user));

        $user->save();
    }

    private function updateVerifiedUser($user, array $input): void
    {
        $name = Name::from($input['name']);

        $user->forceFill([
            'first_name' => $name->first,
            'last_name' => $name->last,
            'email' => $input['email'],
            'timezone' => $input['timezone'],
            'email_verified_at' => null,
        ]);

        event(new UserProfileInformationUpdatedEvent($user));

        $user->save();

        $user->sendEmailVerificationNotification();
    }
}
