<?php

declare(strict_types=1);

use App\Enums\RepositoryType;
use App\Livewire\Front\Repositories;
use App\Models\Repository;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->records = Repository::factory()
        ->sequence(
            ['type' => RepositoryType::Package],
            ['type' => RepositoryType::Project],
        )
        ->visible()
        ->count(6)
        ->create();
});

it('renders', function () {
    livewire(Repositories::class)
        ->assertSuccessful();
});

it('can show repositories of a given type', function (string $key, RepositoryType $type) {
    $records = $this->records->where('type', $type);

    $component = livewire(Repositories::class, ['type' => $key])
        ->assertCount('repositories', 3)
        ->assertSet('total', 3)
        ->assertSet('hasMore', false);

    foreach ($records as $record) {
        $component->assertSee(
            'wire:key="repositories.' . $record->name . '"',
            escape: false,
        );
    }
})->with([
    ['packages', RepositoryType::Package],
    ['projects', RepositoryType::Project],
]);

it('shows the correct search placeholder for each type', function (string $type, string $placeholder) {
    livewire(Repositories::class, ['type' => $type])
        ->assertSet('searchPlaceholder', $placeholder);
})->with([
    ['packages', 'Search packages...'],
    ['projects', 'Search projects...'],
]);

it('can load more repositories', function () {
    $records = $this->records
        ->where('type', RepositoryType::Package)
        ->sortBy('name')
        ->values();

    livewire(Repositories::class, ['type' => 'packages', 'pageSize' => 1, 'sort' => 'name'])
        ->assertCount('repositories', 1)
        ->assertSet('repositories.0.id', $records[0]->id)
        ->call('loadMore')
        ->assertCount('repositories', 2)
        ->assertSet('repositories.1.id', $records[1]->id)
        ->assertSet('hasMore', true)
        ->call('loadMore')
        ->assertCount('repositories', 3)
        ->assertSet('repositories.2.id', $records[2]->id)
        ->assertSet('hasMore', false);
});

it('can search repositories', function () {
    $record = $this->records
        ->where('type', RepositoryType::Package)
        ->first();

    livewire(Repositories::class, ['type' => 'packages'])
        ->assertCount('repositories', 3)
        ->set('search', $record->name)
        ->assertCount('repositories', 1)
        ->assertSet('repositories.0.id', $record->id)
        ->assertSet('hasMore', false);
});

it('does not show hidden repositories', function () {
    $records = $this->records
        ->where('type', RepositoryType::Package);

    $records->first()->update(['visible' => false]);

    livewire(Repositories::class, ['type' => 'packages'])
        ->assertCount('repositories', 2)
        ->assertDontSee(
            'wire:key="records.' . $records->first()->name . '"',
            escape: false,
        );
});
