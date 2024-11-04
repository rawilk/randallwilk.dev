<?php

declare(strict_types=1);

use function Pest\Laravel\get;

beforeEach(function () {
    config()->set('cache.stores.docs.driver', 'array');

    $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

    config()->set('docs.repositories', $repositoriesWithDocs->only('rawilk/laravel-settings'));

    $this->repositoryConfig = $repositoriesWithDocs['rawilk/laravel-settings'];

    $this->disk = Storage::fake('docs_laravel-settings');

    // Copy files from tests/Fixtures/stubs/docs/laravel-settings to disk root
    $stubPath = __DIR__ . '/../../Fixtures/stubs/docs/laravel-settings';
    File::copyDirectory(
        $stubPath,
        $this->disk->path('/'),
    );
});

it('renders a doc page', function () {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'installation',
    ]);

    get($uri)
        ->assertOk()
        ->assertSeeText('Installation')
        ->assertSeeText('Migrations');
});

it('renders a doc page for a previous version', function () {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v2',
        'slug' => 'installation',
    ]);

    get($uri)
        ->assertSeeText('V2 Migrations');
});

it('redirects to the latest version if the alias does not exist', function () {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v4',
        'slug' => 'installation',
    ]);

    $expectedRedirect = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'installation',
    ]);

    get($uri)
        ->assertRedirect($expectedRedirect);
});

it('redirects to the root repository url if the page slug is not found', function () {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'invalid',
    ]);

    $expectedRedirect = route('docs.repository', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
    ]);

    get($uri)
        ->assertRedirect($expectedRedirect);
});

it('throws a 404 when the repository is not found', function () {
    $uri = route('docs.show', [
        'repository' => 'invalid',
        'alias' => 'v1',
        'slug' => 'foo',
    ]);

    get($uri)->assertNotFound();
});

it('throws a 404 when the alias is not found on the root repository url', function () {
    $uri = route('docs.repository', [
        'repository' => 'laravel-settings',
        'alias' => 'v4',
    ]);

    get($uri)->assertNotFound();
});

it('redirects to the first available page on the root repository url', function () {
    $uri = route('docs.repository', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
    ]);

    $expectedRedirect = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'introduction',
    ]);

    get($uri)->assertRedirect($expectedRedirect);
});

it('redirects to the latest alias and page if no alias is provided on the root repository url', function () {
    $uri = route('docs.repository', [
        'repository' => 'laravel-settings',
    ]);

    $expectedRedirect = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'introduction',
    ]);

    get($uri)->assertRedirect($expectedRedirect);
});

it('can show the contents of a nested doc page', function () {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'best-practices/enums',
    ]);

    get($uri)
        ->assertOk()
        ->assertSeeText('PHP 8.1 introduced native enums');
});

it('shows an archival notice for archived repositories', function () {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'installation',
    ]);

    get($uri)
        ->assertDontSeeText('has been archived and is no longer maintained.');

    config([
        'docs.repositories' => [
            [
                ...$this->repositoryConfig,
                'archived' => true,
            ],
        ],
    ]);

    get($uri)
        ->assertSeeText('has been archived and is no longer maintained.');
});

it('shows an alert for outdated versions', function (string $alias, bool $shouldSee) {
    $uri = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => $alias,
        'slug' => 'installation',
    ]);

    get($uri)
        ->when(
            $shouldSee,
            fn ($response) => $response->assertSeeText('for an old version'),
            fn ($response) => $response->assertDontSeeText('for an old version'),
        );
})->with([
    ['v3', false],
    ['v2', true],
    ['v1', true],
]);
