<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Timebox;
use Rawilk\ProfileFilament\Database\Factories\AuthenticatorAppFactory;
use Rawilk\ProfileFilament\Models\AuthenticatorApp;
use Tests\Fixtures\Services\Location\LocationTestDriver;
use Tests\Fixtures\Support\InstantlyResolvingTimebox;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clearing cache to clear out rate limit hits, so each
        // test starts fresh.
        cache()->clear();

        $this->app->bind(Timebox::class, InstantlyResolvingTimebox::class);

        Factory::guessFactoryNamesUsing(function (string $modelName): string {
            if ($modelName === AuthenticatorApp::class) {
                return AuthenticatorAppFactory::class;
            }

            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });

        Storage::fake('tmp-for-tests');
    }

    protected function fakeIpLocation(): void
    {
        config()->set('location.driver', LocationTestDriver::class);
    }
}
