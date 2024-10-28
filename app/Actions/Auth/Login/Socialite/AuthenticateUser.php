<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Dto\Auth\GitHubLoginBag;
use Closure;

class AuthenticateUser
{
    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        if (! $request->shouldLogin()) {
            return $next($request);
        }

        auth()->login($request->user(), true);

        session()->regenerate();

        session()->put('github.login', true);

        return $next($request);
    }
}
