<?php

declare(strict_types=1);

namespace App\View\Components\Button;

use App\View\Components\BladeComponent;

class Button extends BladeComponent
{
    public string $variant;
    public bool $block;
    public bool $icon;
    public string $containerClass;

    public function __construct(string $variant = 'primary', bool $block = false, bool $icon = false, string $containerClass = '')
    {
        $this->variant = $variant;
        $this->block = $block;
        $this->icon = $icon;
        $this->containerClass = $containerClass;
    }
}
