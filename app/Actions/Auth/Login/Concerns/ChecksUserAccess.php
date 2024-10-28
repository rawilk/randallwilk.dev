<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Concerns;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

trait ChecksUserAccess
{
    protected function userCanAccessSystem(User $user, #[SensitiveParameter] array $data = []): bool
    {
        // TODO: Add user suspension/checking here.

        return true;
    }

    protected function shouldRemember(#[SensitiveParameter] array $data): bool
    {
        return data_get($data, 'remember', false) === true;
    }

    protected function throwFailedAuthenticationException(): void
    {
        throw ValidationException::withMessages([
            'data.email' => __('auth.failed'),
        ]);
    }
}
