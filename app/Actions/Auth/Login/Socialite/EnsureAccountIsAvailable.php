<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Dto\Auth\GitHubLoginBag;
use App\Enums\SessionAlert;
use App\Models\User;
use Closure;
use Laravel\Socialite\Two\User as SocialiteUser;

/**
 * In theory, the GitHub account should not be taken by another user,
 * but this is here to help prevent two users from linking their
 * account to the same GitHub account.
 */
class EnsureAccountIsAvailable
{
    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        if ($this->accountIsAlreadyTaken($request->user(), $request->gitHubUser())) {
            SessionAlert::Error->flash(__('auth.socialite.alerts.already_linked'));

            return view('auth.github-callback', [
                'panelId' => $request->panelId(),
            ]);
        }

        return $next($request);
    }

    protected function accountIsAlreadyTaken(User $user, SocialiteUser $githubUser): bool
    {
        if ($user->github_id === $githubUser->getId()) {
            return false;
        }

        return User::where('github_id', $githubUser->getId())->exists();
    }
}
