<?php

declare(strict_types=1);

namespace App\View\Components\Concerns;

use Illuminate\View\ComponentSlot;

trait AcceptsComponentSlots
{
    public function componentSlot(mixed $slot): ComponentSlot
    {
        return $slot instanceof ComponentSlot
            ? $slot
            : new ComponentSlot;
    }
}
