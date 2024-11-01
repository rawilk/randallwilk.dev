<?php

declare(strict_types=1);

use App\Docs\DocumentationPage;

it('knows if it is an index page', function (string $slug, bool $isIndex) {
    $page = new DocumentationPage;
    $page->slug = $slug;

    expect($page->isIndex())->toBe($isIndex);
})->with([
    ['_index', true],
    ['foo_index', true],
    ['index', false],
    ['_index1', false],
]);

it('knows if it is a root section page', function (string $slug, bool $isRoot) {
    $page = new DocumentationPage;
    $page->slug = $slug;

    expect($page->isRootPage())->toBe($isRoot);
})->with([
    ['installation', true],
    ['usage/foo', false],
]);

it('can get the GitHub documentation markdown file url', function () {
    $page = new DocumentationPage;
    $page->githubUrl = 'https://github.com/rawilk/laravel-settings';
    $page->branch = 'main';
    $page->slug = 'api/settings';

    expect($page->editUrl())->toBe('https://github.com/rawilk/laravel-settings/blob/main/docs/api/settings.md');
});

it('has a url', function () {
    $page = new DocumentationPage;
    $page->repository = 'laravel-settings';
    $page->alias = 'v3';
    $page->slug = 'api/settings';

    $expectedUrl = route('docs.show', [
        'repository' => 'laravel-settings',
        'alias' => 'v3',
        'slug' => 'api/settings',
    ]);

    expect($page->url)->toBe($expectedUrl);
});

it('renders a version in the format of 1.x', function (string $alias, string $version) {
    $page = new DocumentationPage;
    $page->alias = $alias;

    expect($page->versionSelectAlias())->toBe($version);
})->with([
    ['v1', '1.x'],
    ['2', '2.x'],
    ['v0', '0.x'],
]);
