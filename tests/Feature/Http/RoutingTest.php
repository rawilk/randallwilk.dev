<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function Spatie\RouteTesting\routeTesting;

routeTesting('front-end routes')
    ->exclude(
        '_ignition*',
        'admin*',
        'api*',
        'docs/*',
        'filament*',
        'livewire*',
        'projects',
        'sessions*',
        'storage*',
        'up',
    )
    ->assertSuccessful();

routeTesting('docs repository routes')
    ->include('docs/{repository}/{alias?}')
    ->setUp(function () {
        config()->set('cache.stores.docs.driver', 'array');

        $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

        config()->set('docs.repositories', $repositoriesWithDocs->only('rawilk/laravel-settings'));

        $disk = Storage::fake('docs_laravel-settings');

        File::copyDirectory(
            __DIR__ . '/../../TestSupport/stubs/docs/laravel-settings',
            $disk->path('/'),
        );
    })
    ->bind('repository', fn () => 'laravel-settings')
    ->bind('alias', fn () => 'v3')
    ->assertRedirect();

routeTesting('docs page routes')
    ->include('docs/{repository}/{alias}/{slug}')
    ->setUp(function () {
        config()->set('cache.stores.docs.driver', 'array');

        $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

        config()->set('docs.repositories', $repositoriesWithDocs->only('rawilk/laravel-settings'));

        $disk = Storage::fake('docs_laravel-settings');

        File::copyDirectory(
            __DIR__ . '/../../TestSupport/stubs/docs/laravel-settings',
            $disk->path('/'),
        );
    })
    ->bind('repository', fn () => 'laravel-settings')
    ->bind('alias', fn () => 'v3')
    ->bind('slug', fn () => 'installation')
    ->assertSuccessful();

routeTesting('redirects')
    ->include('projects')
    ->assertRedirect();
