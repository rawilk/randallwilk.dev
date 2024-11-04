<?php

declare(strict_types=1);

use Rawilk\ProfileFilament\Filament\Pages\Profile\Sessions;
use Rawilk\ProfileFilament\Livewire\Sessions\SessionManager;

use function Pest\Laravel\be;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = adminUser();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

// Just doing a smoke test for this page since we're not customizing the components at all, and they're
// all tested in the profile-filament plugin source.

it('renders', function () {
    get(Sessions::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSeeLivewire(SessionManager::class);
});
