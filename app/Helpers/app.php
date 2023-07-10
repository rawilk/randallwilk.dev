<?php

declare(strict_types=1);

namespace App\Helpers;

use Rawilk\FormComponents\Support\TimeZoneRegionEnum;

function timezoneSubsets(): array
{
    return [
        TimeZoneRegionEnum::General->value,
        TimeZoneRegionEnum::America->value,
    ];
}
