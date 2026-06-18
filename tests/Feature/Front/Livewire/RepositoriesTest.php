<?php

declare(strict_types=1);

use App\Enums\RepositoryType;
use App\Livewire\Front\Repositories;
use App\Models\Repository;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->component = Repositories::class;

    $this->packages = Repository::factory()
        ->visible()
        ->sequence(
            ['name' => 'zeta-package', 'type' => RepositoryType::Package, 'description' => 'Last package'],
            ['name' => 'alpha-package', 'type' => RepositoryType::Package, 'description' => 'First package'],
        )
        ->count(2)
        ->create();

    $this->projects = Repository::factory()
        ->visible()
        ->sequence(
            ['name' => 'zeta-project', 'type' => RepositoryType::Project, 'description' => 'Last project'],
            ['name' => 'alpha-project', 'type' => RepositoryType::Project, 'description' => 'First project'],
        )
        ->count(2)
        ->create();

    $this->hiddenPackage = Repository::factory()
        ->hidden()
        ->package()
        ->create(['name' => 'hidden-package']);

    $this->hiddenProject = Repository::factory()
        ->hidden()
        ->project()
        ->create(['name' => 'hidden-project']);
});

it('renders', function () {
    livewire($this->component)
        ->assertSuccessful();
});

it('can show visible repositories of a given type', function (string $key, RepositoryType $type) {
    $records = $type === RepositoryType::Package
        ? $this->packages
        : $this->projects;

    $component = livewire($this->component, ['type' => $key]);

    foreach ($records as $record) {
        $component->assertSee($record->name);
    }

    $component
        ->assertDontSee($this->hiddenPackage->name)
        ->assertDontSee($this->hiddenProject->name);
})->with([
    ['packages', RepositoryType::Package],
    ['projects', RepositoryType::Project],
]);

it('renders repositories alphabetically', function () {
    livewire($this->component, ['type' => 'packages'])
        ->assertSeeInOrder([
            'alpha-package',
            'zeta-package',
        ]);
});

it('shows the correct search placeholder for each type', function (string $type, string $placeholder) {
    livewire($this->component, ['type' => $type])
        ->assertSeeHtml('placeholder="' . $placeholder . '"');
})->with([
    ['packages', 'Search packages...'],
    ['projects', 'Search projects...'],
]);

it('uses the initial search query when rendering results', function () {
    livewire($this->component, [
        'type' => 'packages',
        'search' => 'alpha',
    ])
        ->assertSeeHtml('value="alpha"');
});
