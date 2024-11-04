<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Timebox;
use Rawilk\ProfileFilament\Database\Factories\AuthenticatorAppFactory;
use Rawilk\ProfileFilament\Models\AuthenticatorApp;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;
use Tests\Fixtures\Support\InstantlyResolvingTimebox;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clearing cache to clear out rate limit hits, so each
        // test starts fresh.
        cache()->clear();

        Http::preventStrayRequests();

        $this->app->bind(Timebox::class, InstantlyResolvingTimebox::class);

        Factory::guessFactoryNamesUsing(function (string $modelName): string {
            if ($modelName === AuthenticatorApp::class) {
                return AuthenticatorAppFactory::class;
            }

            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });

        Storage::fake('tmp-for-tests');

        $this->fakeIpLocation();
    }

    protected function fakeIpLocation(): void
    {
        Location::fake([
            '127.0.0.1' => Position::make([
                'countryName' => 'United States',
                'countryCode' => 'US',
                'regionCode' => null,
                'regionName' => 'Wisconsin',
                'cityName' => 'Madison',
                'zipCode' => '53703',
                'latitude' => '43.0731',
                'longitude' => '-89.4012',
                'timezone' => 'America/Chicago',
            ]),
        ]);
    }
}
