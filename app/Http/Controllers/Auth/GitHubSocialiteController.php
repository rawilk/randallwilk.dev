<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Login\Socialite as LoginActions;
use App\Dto\Auth\GitHubLoginBag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Laravel\Socialite\Facades\Socialite;

class GitHubSocialiteController
{
    public function redirect(Request $request): RedirectResponse
    {
        session()->reflash();

        if ($request->filled('p')) {
            session()->put('panel', $request->input('p'));
        }

        if (auth()->check()) {
            /**
             * If somebody is already logged in, the user wants to
             * connect their GitHub profile. Remember who's logged in.
             */
            session()->put('auth-user-id', auth()->id());
        }

        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        return Pipeline::send(
            new GitHubLoginBag(Socialite::driver('github'))
        )->through([
            LoginActions\EnsureValidGitHubUser::class,
            LoginActions\ResolveSocialiteUser::class,
            LoginActions\EnsureAccountIsAvailable::class,
            LoginActions\SyncUserWithGitHub::class,
            LoginActions\EnsureUserIsActive::class,
            LoginActions\RedirectIfUserHasMfa::class,
            LoginActions\AuthenticateUser::class,
        ])->then(function (GitHubLoginBag $bag) {
            return view('auth.github-callback', [
                'panelId' => $bag->panelId(),
                'redirect' => $bag->redirect(),
            ]);
        });
    }
}
