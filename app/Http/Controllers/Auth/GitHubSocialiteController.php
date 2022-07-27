<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelCasters\Support\Name;

final class GitHubSocialiteController
{
    private ?string $newUserPassword = null;

    public function redirect(): RedirectResponse
    {
        Session::reflash();

        if (Auth::check()) {
            /*
             * If somebody is already logged in, the user wants to
             * connect their GitHub profile. Remember who's logged in.
             */
            Session::put('auth-user-id', Auth::id());
        }

        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();
        } catch (Exception) {
            Session::flash(Alert::ERROR, __('auth.socialite.alerts.github_auth_process_failed'));

            return redirect()->route(Auth::check() ? 'profile.authentication' : 'login');
        }

        $user = $this->retrieveUser($githubUser);

        /*
         * In theory, this shouldn't happen. However, if for some reason
         * another user account already has the requested GitHub account
         * linked to it, we'll prevent two users from linking to
         * the same GitHub account.
         */
        if ($this->accountAlreadyTaken($user, $githubUser)) {
            return view('auth.github-callback');
        }

        $user->update([
            'github_id' => $githubUser->getId(),
            'github_username' => $githubUser->getNickname(),
            'avatar_path' => $user->avatar_path ?: $githubUser->getAvatar(), // Only update avatar if user doesn't have already have one.
        ]);

        if ($user->wasRecentlyCreated && $this->newUserPassword) {
        }

        Auth::login($user, true);

        Session::flash(Alert::SUCCESS, __('auth.socialite.alerts.login_success'));

        return view('auth.github-callback');
    }

    private function accountAlreadyTaken(User $user, $githubUser): bool
    {
        if ($user->github_id === $githubUser->getId()) {
            return false;
        }

        return User::where('github_id', $githubUser->getId())->exists();
    }

    private function retrieveUser($githubUser): User
    {
        if (Session::has('auth-user-id')) {
            /*
             * If there already was a local user created for the email used
             * on GitHub, then let's use that local user.
             */
            return User::find(Session::pull('auth-user-id'));
        }

        if ($githubUser->email && $user = User::where('email', $githubUser->email)->first()) {
            /*
             * Somebody tries to login via GitHub that already
             * has account with this email.
             * We'll link this GitHub profile to this account.
             */
            return $user;
        }

        $name = Name::from($githubUser->name() ?? $githubUser->getNickname());

        return User::firstOrCreate([
            'github_id' => $githubUser->getId(),
        ], [
            'password' => $this->newUserPassword = Str::random(),
            'email' => $githubUser->email(),
            'first_name' => $name->first,
            'last_name' => $name->last,
            'timezone' => appTimezone(),
        ]);
    }
}
