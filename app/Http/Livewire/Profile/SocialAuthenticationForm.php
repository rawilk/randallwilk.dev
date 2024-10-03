<?php

declare(strict_types=1);

namespace App\Http\Livewire\Profile;

use App\Actions\Profile\DisconnectFromGitHubAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * @property-read User $user
 */
final class SocialAuthenticationForm extends Component
{
    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function disconnectFromGitHub(DisconnectFromGitHubAction $action): void
    {
        if (! Auth::user()->github_id) {
            return;
        }

        $action(Auth::user());

        $this->notify(__('users.profile.alerts.github_disconnected'));
    }
}
