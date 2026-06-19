<?php

declare(strict_types=1);

use App\Livewire\Profile\ProfileInfo as ProfileInfoForm;
use Filament\Actions\Testing\TestAction;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

use function Pest\Laravel\be;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

function profileInfoEditAction(): TestAction
{
    return TestAction::make('edit')->schemaComponent('profile-information', 'infolist');
}

beforeEach(function () {
    $this->user = adminUser();
    $this->component = ProfileInfoForm::class;

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    get(ProfileInfo::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSeeLivewire($this->component);
});

it('can edit a users profile info', function () {
    $editAction = profileInfoEditAction();

    livewire($this->component)
        ->mountAction($editAction)
        ->assertActionDataSet([
            'name' => $this->user->name->full,
            'timezone' => $this->user->timezone,
        ])
        ->callAction($editAction, data: [
            'name' => 'New name',
            'timezone' => 'America/New_York',
        ])
        ->assertHasNoActionErrors();

    expect($this->user->refresh())
        ->name->full->toBe('New name')
        ->timezone->toBe('America/New_York');
});

describe('validation', function () {
    it('requires a name', function () {
        livewire($this->component)
            ->callAction(profileInfoEditAction(), data: [
                'name' => '',
                'timezone' => $this->user->timezone,
            ])
            ->assertHasActionErrors([
                'name' => ['required'],
            ]);
    });

    it('requires a name under 255 characters', function () {
        livewire($this->component)
            ->callAction(profileInfoEditAction(), data: [
                'name' => str_repeat('a', 256),
                'timezone' => $this->user->timezone,
            ])
            ->assertHasActionErrors([
                'name' => ['max'],
            ]);
    });

    it('requires a timezone', function () {
        livewire($this->component)
            ->callAction(profileInfoEditAction(), data: [
                'name' => $this->user->name->full,
                'timezone' => '',
            ])
            ->assertHasActionErrors([
                'timezone' => ['required'],
            ]);
    });
});
