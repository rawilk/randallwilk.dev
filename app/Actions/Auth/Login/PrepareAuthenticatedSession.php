<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login;

use App\Dto\Auth\LoginEventBag;
use Closure;

class PrepareAuthenticatedSession
{
    public function __invoke(LoginEventBag $request, Closure $next)
    {
        session()->regenerate();

        return $next($request);
    }
}
