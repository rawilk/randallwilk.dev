<?php

declare(strict_types=1);

use App\Livewire\Profile\PreferredMfaMethod;
use Rawilk\ProfileFilament\Filament\Pages\Profile\Security;
use Rawilk\ProfileFilament\Livewire\MfaOverview;
use Rawilk\ProfileFilament\Livewire\PasskeyManager;
use Rawilk\ProfileFilament\Livewire\UpdatePassword;

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
    get(Security::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSeeLivewire(UpdatePassword::class)
        ->assertSeeLivewire(PasskeyManager::class)
        ->assertSeeLivewire(MfaOverview::class)
        ->assertSeeLivewire(PreferredMfaMethod::class);
});
