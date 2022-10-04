<?php

declare(strict_types=1);

use Rawilk\FormComponents\Support\TimeZoneRegion;

if (! function_exists('timezoneSubsets')) {
    /**
     * The timezone regions we normally need to include.
     *
     * @return array<int, string>
     */
    function timezoneSubsets(): array
    {
        return [
            TimeZoneRegion::GENERAL,
            TimeZoneRegion::AMERICA,
        ];
    }
}
