<?php

declare(strict_types=1);

namespace App\Filament\Actions\Profile;

use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Rawilk\ProfileFilament\Filament\Actions\Sudo\Concerns\RequiresSudo;

class ConnectGitHubAction extends Action
{
    use RequiresSudo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('users/profile.connected_accounts.actions.connect.label'));

        $this->color('primary');

        $this->hidden(fn (User $record): bool => filled($record->github_id));

        $this->action(function () {
            $url = URL::temporarySignedRoute(
                'login.github',
                now()->addMinute(),
                [
                    'p' => filament()->getId(),
                ],
            );

            redirect($url);
        });

        $this->mountUsing(function (Component $livewire) {
            $this->mountSudoAction($livewire);
        });

        $this->registerModalActions([
            $this->getSudoChallengeAction(),
        ]);
    }

    public static function getDefaultName(): ?string
    {
        return 'connectGitHub';
    }
}
