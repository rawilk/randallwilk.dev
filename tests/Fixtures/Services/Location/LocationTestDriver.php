<?php

declare(strict_types=1);

namespace Tests\Fixtures\Services\Location;

use Illuminate\Support\Fluent;
use Stevebauman\Location\Drivers\Driver;
use Stevebauman\Location\Position;
use Stevebauman\Location\Request;

final class LocationTestDriver extends Driver
{
    protected function process(Request $request): Fluent|false
    {
        $response = [
            'ip' => $request->getIp(),
            'countryName' => 'United States',
            'countryCode' => 'US',
            'regionCode' => null,
            'regionName' => 'Wisconsin',
            'cityName' => 'Madison',
            'zipCode' => '53703',
            'latitude' => '43.0731',
            'longitude' => '-89.4012',
            'timezone' => 'America/Chicago',
        ];

        return new Fluent($response);
    }

    protected function hydrate(Position $position, Fluent $location): Position
    {
        $position->countryName = $location->countryName;
        $position->countryCode = $location->countryCode;
        $position->regionCode = $location->regionCode;
        $position->regionName = $location->regionName;
        $position->cityName = $location->cityName;
        $position->zipCode = $location->zipCode;
        $position->latitude = $location->latitude;
        $position->longitude = $location->longitude;
        $position->timezone = $location->timezone;

        return $position;
    }
}
