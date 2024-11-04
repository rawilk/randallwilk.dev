<?php

declare(strict_types=1);

use function Pest\Laravel\be;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = adminUser();

    be($this->user);

    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('renders', function () {
    get(filament()->getUrl())->assertOk();
});
