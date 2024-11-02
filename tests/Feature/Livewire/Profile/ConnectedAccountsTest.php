<?php

declare(strict_types=1);

use App\Livewire\Profile\ConnectedAccounts;
use App\Models\User;

use function Pest\Laravel\be;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    livewire(ConnectedAccounts::class)->assertOk();
});
