<?php

declare(strict_types=1);

namespace App\Filament\Actions\Profile;

use App\Models\User;
use App\Notifications\Auth\ConnectedAccounts\GitHubDisconnectedNotification;
use App\Notifications\Concerns\SetsDeviceDetails;
use Filament\Actions\Action;
use Livewire\Component;
use Rawilk\ProfileFilament\Filament\Actions\Sudo\Concerns\RequiresSudo;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

class DisconnectGitHubAction extends Action
{
    use RequiresSudo;
    use SetsDeviceDetails;

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('danger');

        $this->label(__('users/profile.connected_accounts.actions.disconnect.label'));

        $this->successNotificationTitle(__('users/profile.connected_accounts.actions.disconnect.success'));

        $this->action(function (User $record) {
            $record->update([
                'github_id' => null,
                'github_username' => null,
            ]);

            $notification = new GitHubDisconnectedNotification;
            $notification
                ->setUrl(ProfileInfo::getUrl(panel: filament()->getId()));

            $this->setDeviceDetailsOn($notification, $record);

            $record->notify($notification);

            $this->success();
        });

        $this->visible(fn (User $record): bool => filled($record->github_id));

        $this->mountUsing(function (Component $livewire) {
            $this->mountSudoAction($livewire);
        });

        $this->registerModalActions([
            $this->getSudoChallengeAction(),
        ]);
    }

    public static function getDefaultName(): ?string
    {
        return 'disconnectGitHub';
    }
}
