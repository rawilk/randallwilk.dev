<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Actions\Auth\Login\Concerns\ChecksUserAccess;
use App\Dto\Auth\GitHubLoginBag;
use App\Enums\SessionAlert;
use Closure;

class EnsureUserIsActive
{
    use ChecksUserAccess;

    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        // This is only necessary for login requests.
        if (! $request->isLoginRequest()) {
            return $next($request);
        }

        if (! $this->userCanAccessSystem($request->user())) {
            SessionAlert::Error->flash('Your account is suspended.');

            return view('auth.github-callback', [
                'panelId' => $request->panelId(),
                'redirect' => $request->redirect(),
            ]);
        }

        return $next($request);
    }
}
