<?php

declare(strict_types=1);

use App\Livewire\Profile\ConnectedAccounts;
use App\Livewire\Profile\ProfileInfo as ProfileInfoForm;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = adminUser();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    get(ProfileInfo::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSeeLivewire(ProfileInfoForm::class)
        ->assertSeeLivewire(ConnectedAccounts::class);
});

it('can edit a users profile info', function () {
    livewire(ProfileInfoForm::class)
        ->mountInfolistAction('profile-information', 'edit')
        ->assertInfolistActionDataSet([
            'name' => $this->user->name->full,
            'timezone' => $this->user->timezone,
        ])
        ->callInfolistAction('profile-information', 'edit', data: [
            'name' => 'New name',
            'timezone' => 'America/New_York',
        ])
        ->assertHasNoInfolistActionErrors();

    expect($this->user->refresh())
        ->name->full->toBe('New name')
        ->timezone->toBe('America/New_York');
});
