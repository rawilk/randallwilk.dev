<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Timebox;
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
    }

    protected function fakeIpLocation(): void
    {
        config()->set('location.driver', LocationTestDriver::class);
    }
}
