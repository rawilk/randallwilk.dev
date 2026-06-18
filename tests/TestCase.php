<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Timebox;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;
use Tests\TestSupport\Services\InstantlyResolvingTimebox;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        $this->app->bind(Timebox::class, InstantlyResolvingTimebox::class);

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
