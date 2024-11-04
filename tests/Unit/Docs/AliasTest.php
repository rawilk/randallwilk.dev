<?php

declare(strict_types=1);

use App\Docs\Alias;
use App\Docs\DocumentationPage;

beforeEach(function () {
    $this->page = new DocumentationPage;

    $this->page->slug = '_index';
    $this->page->slogan = 'Persistent settings for Laravel applications.';
    $this->page->githubUrl = 'https://github.com/rawilk/laravel-settings';
    $this->page->branch = 'main';
});

it('can calculate the version number', function (string $title, int $version) {
    $this->page->alias = $title;
    $this->page->title = $title;

    $alias = Alias::fromDocumentationPage($this->page, collect());

    expect($alias)->versionNumber
        ->toBeInt()
        ->toBe($version);
})->with([
    ['v3', 3],
    ['v2', 2],
    ['1', 1],
    ['prefix1Suffix', 1],
]);

it('can generate a string for version select', function (string $title, string $versionAlias) {
    $this->page->alias = $title;
    $this->page->title = $title;

    $alias = Alias::fromDocumentationPage($this->page, collect());

    expect($alias)->versionSelectAlias()->toBe($versionAlias);
})->with([
    ['v3', '3.x'],
    ['v2', '2.x'],
    ['prefix1Suffix', '1.x'],
    ['v0', '0.x'],
]);

it('can get the GitHub url for its branch', function (string $branch) {
    $this->page->branch = $branch;
    $this->page->title = 'v3';
    $this->page->alias = 'v3';

    $alias = Alias::fromDocumentationPage($this->page, collect());

    expect($alias)->branchUrl()->toBe("https://github.com/rawilk/laravel-settings/tree/{$branch}");
})->with([
    'main',
    'master',
    'v3',
]);
