<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Enums\SessionAlert;
use App\Filament\Actions\Profile\ConnectGitHubAction;
use App\Filament\Actions\Profile\DisconnectGitHubAction;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read User $user
 */
class ConnectedAccounts extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public static int $sort = 5;

    #[Computed]
    public function user(): User
    {
        return auth()->user();
    }

    public function mount(): void
    {
        if (session()->pull('github.connected') === true) {
            $message = SessionAlert::Success->message(pull: true);

            SessionAlert::Success->flash($message);
        }
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-filament::section
                :heading="__('users/profile.connected_accounts.heading')"
            >
                <ul>
                    {{-- GitHub --}}
                    <x-profile.connected-account
                        icon="svg-github"
                        :heading="__('users/profile.connected_accounts.github.heading')"
                        :placeholder="__('users/profile.connected_accounts.github.placeholder')"
                        :username="$this->user->github_username"
                        data-provider="github"
                    >
                        @if (filled($this->user->github_id))
                            {{ $this->disconnectGitHubAction }}
                        @else
                            {{ $this->connectGitHubAction }}
                        @endif
                    </x-profile.connected-account>
                </ul>
            </x-filament::section>

            <x-filament-actions::modals />
        </div>
        HTML;
    }

    public function connectGitHubAction(): Action
    {
        return ConnectGitHubAction::make()
            ->record($this->user);
    }

    public function disconnectGitHubAction(): Action
    {
        return DisconnectGitHubAction::make()
            ->record($this->user);
    }
}
