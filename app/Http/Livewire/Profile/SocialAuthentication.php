<?php

declare(strict_types=1);

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/** @property-read \App\Models\User $user */
final class SocialAuthentication extends Component
{
    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function disconnectFromGithub(): void
    {
        if (! $this->user->github_id) {
            return;
        }

        Auth::user()->update([
            'github_id' => null,
            'github_username' => null,
        ]);

        $this->notify(__('users.profile.authentication.account_was_disconnected', ['provider' => 'GitHub']));
    }

    public function render(): View
    {
        return view('livewire.profile.social-authentication');
    }
}
