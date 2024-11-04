<?php

declare(strict_types=1);

use App\Filament\Actions\Profile\ConnectGitHubAction;
use App\Filament\Actions\Profile\DisconnectGitHubAction;
use App\Livewire\Profile\ConnectedAccounts;
use App\Models\User;
use App\Notifications\Auth\ConnectedAccounts\GitHubDisconnectedNotification;
use Rawilk\ProfileFilament\Facades\Sudo;

use function Pest\Laravel\be;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    livewire(ConnectedAccounts::class)
        ->assertOk();
});

it('has an action to connect a users GitHub account', function () {
    livewire(ConnectedAccounts::class)
        ->assertActionExists(ConnectGitHubAction::class);
});

test('the connect action redirects to the GitHub login controller', function () {
    Sudo::activate();

    $this->freezeSecond();

    $expectedUri = URL::temporarySignedRoute(
        'login.github',
        now()->addMinute(),
        [
            'p' => filament()->getId(),
        ],
    );

    livewire(ConnectedAccounts::class)
        ->callAction(ConnectGitHubAction::class)
        ->assertRedirect($expectedUri);
});

it('requires sudo mode to connect a GitHub account', function () {
    livewire(ConnectedAccounts::class)
        ->call('mountAction', ConnectGitHubAction::getDefaultName())
        ->assertSeeText(sudoChallengeTitle())
        ->assertNoRedirect();
});

it('has an action to disconnect a GitHub account from a user', function () {
    livewire(ConnectedAccounts::class)
        ->assertActionExists(DisconnectGitHubAction::class);
});

it('can disconnect a GitHub account from a user', function () {
    $this->user->update([
        'github_id' => 123456,
        'github_username' => 'example',
    ]);

    Notification::fake();

    Sudo::activate();

    // Expect to see the current connected account in the UI.
    $expectedText = str(__('users/profile.connected_accounts.connected_account', [
        'username' => 'example',
    ]))
        ->inlineMarkdown()
        ->toHtmlString();

    livewire(ConnectedAccounts::class)
        ->assertSeeHtml($expectedText)
        ->callAction(DisconnectGitHubAction::class)
        ->assertHasNoActionErrors()
        ->assertDontSeeHtml($expectedText);

    expect($this->user->refresh())
        ->github_id->toBeNull()
        ->github_username->toBeNull();

    Notification::assertSentTo($this->user, GitHubDisconnectedNotification::class);
});

it('requires sudo mode to disconnect a GitHub account', function () {
    $this->user->update(['github_id' => 123456]);

    livewire(ConnectedAccounts::class)
        ->call('mountAction', DisconnectGitHubAction::getDefaultName())
        ->assertSeeText(sudoChallengeTitle());
});
