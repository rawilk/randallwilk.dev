<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class UpdateProfileInformationAction
{
    public function execute(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'timezone' => ['sometimes', 'required', 'string'],
            'photo' => ['sometimes', 'nullable', 'image' , 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateAvatar($input['photo']);
        }

        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'timezone' => $input['timezone'],
        ])->save();
    }
}
