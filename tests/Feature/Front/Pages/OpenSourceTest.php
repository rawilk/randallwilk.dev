<?php

declare(strict_types=1);

use App\Enums\RepositoryType;
use App\Models\Repository;

beforeEach(function () {
    Repository::factory()
        ->visible()
        ->sequence(
            ['name' => 'alpha-package', 'type' => RepositoryType::Package, 'description' => 'First package'],
            ['name' => 'bravo-package', 'type' => RepositoryType::Package, 'description' => 'Second package'],
            ['name' => 'alpha-project', 'type' => RepositoryType::Project, 'description' => 'First project'],
        )
        ->count(3)
        ->create();
});

it('renders with no accessibility issues', function () {
    visit([
        route('open-source.packages'),
        route('open-source.projects'),
    ])
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues();
});

it('can search repositories', function () {
    visit(route('open-source.packages'))
        ->assertSee('alpha-package')
        ->assertSee('bravo-package')
        ->type('input[type="search"]', 'alpha')
        ->assertScript("new URL(window.location.href).searchParams.get('q')", 'alpha')
        ->assertScript("Array.from(document.querySelectorAll('a[href=\"https://github.com/rawilk/alpha-package\"]')).some((element) => getComputedStyle(element).display !== 'none')")
        ->assertScript("Array.from(document.querySelectorAll('a[href=\"https://github.com/rawilk/bravo-package\"]')).every((element) => getComputedStyle(element).display === 'none')");
});
