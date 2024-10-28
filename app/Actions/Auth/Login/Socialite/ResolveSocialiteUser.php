<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Dto\Auth\GitHubLoginBag;
use App\Enums\SessionAlert;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Socialite\Two\User as SocialiteUser;

class ResolveSocialiteUser
{
    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        try {
            $user = $this->retrieveUser($request->gitHubUser());
        } catch (ModelNotFoundException) {
            SessionAlert::Error->flash(__('auth.alerts.registration_not_allowed'));

            return view('auth.github-callback', [
                'panelId' => $request->panelId(),
                'redirect' => $request->redirect(),
            ]);
        }

        $request->setUser($user);

        return $next($request);
    }

    protected function retrieveUser(SocialiteUser $githubUser): User
    {
        if (session('auth-user-id')) {
            /**
             * If there already was a local user created for the email used
             * on GitHub, then let's use that local user.
             */
            return User::find(session()->pull('auth-user-id'));
        }

        // This is most likely a login request.
        return User::where('github_id', $githubUser->getId())->firstOrFail();
    }
}
