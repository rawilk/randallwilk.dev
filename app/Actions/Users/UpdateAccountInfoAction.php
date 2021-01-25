<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

final class UpdateAccountInfoAction
{
    public function execute(User $user, array $input): void
    {
        $data = Validator::make($input, [
            'is_admin' => ['sometimes', 'boolean'],
        ])->validate();

        $user->forceFill($data)->save();
    }
}
