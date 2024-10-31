<?php

declare(strict_types=1);

use App\Enums\ProgrammingLanguage;
use App\Enums\RepositoryType;
use App\Models\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('knows if it is a package', function () {
    $repository = Repository::factory()->package()->make();

    expect($repository->isPackage())->toBeTrue();
});

it('knows if it an npm package', function () {
    $repository = Repository::factory()->package()->make();

    expect($repository->isNpmPackage())->toBeFalse();

    $repository->language = ProgrammingLanguage::JavaScript;

    expect($repository->isNpmPackage())->toBeTrue();
});

it('has a query scope to get repositories by type', function () {
    $models = Repository::factory()
        ->sequence(
            ['type' => RepositoryType::Package],
            ['type' => RepositoryType::Project],
        )
        ->count(5)
        ->create();

    Repository::factory()->create(['type' => null]);

    $this->assertDatabaseCount(Repository::class, 6);

    $results = Repository::byType(RepositoryType::Package)->get();

    expect($results)
        ->toHaveCount(3)
        ->modelsMatchExactly($models->where('type', RepositoryType::Package));
});

it('has a query scope to get visible repositories only', function () {
    $models = Repository::factory()
        ->sequence(
            ['visible' => true],
            ['visible' => false],
        )
        ->count(5)
        ->create();

    $results = Repository::visible()->get();

    expect($results)
        ->toHaveCount(3)
        ->modelsMatchExactly($models->where('visible', true));
});

it('has a query scope to search by name', function () {
    $models = Repository::factory()
        ->sequence(
            ['name' => 'repo one'],
            ['name' => 'repo two'],
            ['name' => 'repo three'],
        )
        ->count(3)
        ->create();

    $results = Repository::search('repo one')->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results[0])->toBe($models[0]);
});

it('returns a proper name for npm operations', function (Repository $record, string $expectedName) {
    expect($record->nameForNpm())->toBe($expectedName);
})->with([
    'not scoped' => [fn () => Repository::factory()->create(['name' => 'not-scoped']), 'not-scoped'],
    'scoped' => [fn () => Repository::factory()->create(['name' => 'scoped', 'scoped_name' => '@author/scoped-repo']), '@author/scoped-repo'],
]);

it('returns the full GitHub repository name', function () {
    $record = Repository::factory()->make(['name' => 'foo']);

    expect($record->full_name)->toBe('rawilk/foo');
});

it('has an attribute for its GitHub url', function () {
    $record = Repository::factory()->make(['name' => 'foo']);

    expect($record->url)->toBe('https://github.com/rawilk/foo');
});
