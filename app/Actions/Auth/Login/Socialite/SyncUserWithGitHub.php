<?php

declare(strict_types=1);

namespace App\Actions\Auth\Login\Socialite;

use App\Dto\Auth\GitHubLoginBag;
use App\Enums\SessionAlert;
use App\Models\User;
use App\Notifications\Auth\ConnectedAccounts\GitHubConnectedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Closure;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

class SyncUserWithGitHub
{
    use SetsDeviceDetails;

    public function __invoke(GitHubLoginBag $request, Closure $next)
    {
        $gitHubUser = $request->gitHubUser();

        $request->user()->update([
            'github_id' => $gitHubUser->getId(),
            'github_username' => $gitHubUser->getNickname(),
            // Only update avatar if user doesn't already have one.
            'avatar_path' => $request->user()->avatar_path ?: $gitHubUser->getAvatar(),
        ]);

        if (! $request->isLoginRequest()) {
            // Can't flash because it will be gone by the time our redirect happens.
            session()->put(SessionAlert::Success->value, __('users/profile.connected_accounts.actions.connect.success'));

            session()->put('github.connected', true);

            $this->sendNotification($request->user(), $gitHubUser->getNickname(), $request->panelId());
        }

        return $next($request);
    }

    protected function sendNotification(User $user, string $username, string $panelId): void
    {
        $notification = new GitHubConnectedNotification;
        $notification
            ->setUrl(ProfileInfo::getUrl(panel: $panelId))
            ->setUsername($username);

        $this->setDeviceDetailsOn($notification, $user);

        $user->notify($notification);
    }
}
