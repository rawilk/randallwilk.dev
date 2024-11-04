<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Dto\Auth\GitHubLoginBag;
use App\Enums\SessionAlert;
use Closure;

class EnsureValidGitHubUser
{
    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        if (! $request->gitHubUser()) {
            SessionAlert::Error->flash(__('auth.socialite.alerts.github_auth_process_failed'));

            return redirect()->to($request->errorRedirectUrl());
        }

        return $next($request);
    }
}
