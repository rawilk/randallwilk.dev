<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support;

use Illuminate\Support\Timebox;

final class InstantlyResolvingTimebox extends Timebox
{
    public function call(callable $callback, int $microseconds)
    {
        return $callback($this);
    }
}
