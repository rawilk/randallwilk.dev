<?php

declare(strict_types=1);

use Rawilk\ProfileFilament\Filament\Pages\Profile\Settings;
use Rawilk\ProfileFilament\Livewire\DeleteAccount;
use Rawilk\ProfileFilament\Livewire\Emails\UserEmail;

use function Pest\Laravel\be;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = adminUser();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

// We're not going to test the individual components since they're already tested in the profile-filament plugin.

it('renders', function () {
    get(Settings::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSeeLivewire(UserEmail::class)
        ->assertSeeLivewire(DeleteAccount::class);
});
