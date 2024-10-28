<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;

readonly class LogoutAction
{
    public function __construct(protected StatefulGuard $guard)
    {
    }

    public function __invoke(): void
    {
        $this->guard->logout();

        session()->invalidate();

        session()->regenerateToken();
    }
}
