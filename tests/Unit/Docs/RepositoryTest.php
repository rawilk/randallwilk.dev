<?php

declare(strict_types=1);

use App\Docs\Alias;
use App\Docs\Repository;

it('knows if it is archived', function (bool $archived) {
    $config = config('docs.repositories');
    $config[] = [
        'name' => 'test',
        'repository' => 'rawilk/test',
        'branches' => [
            'main' => 'v1',
        ],
        'main_branch' => 'main',
        'category' => 'Laravel',
        'archived' => $archived,
    ];

    config()->set('docs.repositories', $config);

    $repository = new Repository('test', collect(), null);

    expect($repository->isArchived())->toBe($archived);
})->with([true, false]);

test('a repository without the "archived" key in its config is considered not archived', function () {
    $config = config('docs.repositories');
    $config[] = [
        'name' => 'test',
        'repository' => 'rawilk/test',
        'branches' => [
            'main' => 'v1',
        ],
        'main_branch' => 'main',
        'category' => 'Laravel',
    ];

    config()->set('docs.repositories', $config);

    $repository = new Repository('test', collect(), null);

    expect($repository->isArchived())->toBeFalse();
});

test('a repository without a valid config will show as not archived', function () {
    $repository = new Repository('test', collect(), null);

    expect($repository->isArchived())->toBeFalse();
});

it('can get an alias by slug', function () {
    $slogan = 'Test slogan';
    $gitHubUrl = 'https://github.com/rawilk/laravel-settings';

    $alias1 = new Alias('v1', $slogan, 'v1', 1, $gitHubUrl, collect());
    $alias2 = new Alias('v2', $slogan, 'main', 2, $gitHubUrl, collect());

    $repository = new Repository('laravel-settings', collect([$alias2, $alias1]), null);

    expect($repository->getAlias('v1'))->toBe($alias1)
        ->and($repository->getAlias('v2'))->toBe($alias2);
});
