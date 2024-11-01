<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Dto\Auth\GitHubLoginBag;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Rawilk\ProfileFilament\Facades\Mfa;

class RedirectIfUserHasMfa
{
    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        if (! $request->isLoginRequest()) {
            return $next($request);
        }

        if ($this->userHasMfaEnabled($request->user())) {
            return $this->mfaChallengeResponse($request->user(), $request);
        }

        return $next($request);
    }

    protected function mfaChallengeResponse(User $user, GitHubLoginBag $request): View
    {
        Mfa::pushChallengedUser(
            user: $user,
            remember: true,
        );

        session()->put(
            'next',
            route(filament()->getPanel($request->panelId())->generateRouteName('auth.mfa.challenge')),
        );

        session()->put('github.login', true);

        return view('auth.github-callback', [
            'panelId' => $request->panelId(),
            'redirect' => $request->redirect(),
        ]);
    }

    protected function userHasMfaEnabled(User $user): bool
    {
        return Mfa::userHasMfaEnabled($user);
    }
}
