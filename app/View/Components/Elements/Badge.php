<?php

declare(strict_types=1);

namespace App\View\Components\Elements;

use App\View\Components\BladeComponent;

class Badge extends BladeComponent
{
    public function __construct(
        public string $variant = 'gray',
        public bool $large = false,
        public bool $rounded = false,
        public bool $removeable = false,
        public bool $dot = false
    ) {
    }

    public function badgeClass(): string
    {
        return collect([
            'badge',
            $this->variant ? "badge--{$this->variant}" : null,
            $this->large ? 'badge--large' : null,
            $this->rounded ? 'badge--rounded' : null,
        ])->filter()->implode(' ');
    }
}
