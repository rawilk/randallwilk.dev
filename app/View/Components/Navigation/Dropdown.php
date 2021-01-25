<?php

declare(strict_types=1);

namespace App\View\Components\Navigation;

use App\View\Components\BladeComponent;

final class Dropdown extends BladeComponent
{
    public function __construct(
        public bool $right = false,
        public bool $fixedPosition = false,
        public bool $withBackground = false,
        public null|string $triggerText = null
    ) {}
}
