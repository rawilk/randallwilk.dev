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
        public bool $dot = false,
    ) {}

    public function badgeClass(): string
    {
        return collect([
            'badge',
            'inline-flex items-center py-0.5',
            $this->sizeClasses(),
            $this->badgeVariant(),
            $this->rounded ? 'rounded' : 'rounded-full',
        ])->filter()->implode(' ');
    }

    private function sizeClasses(): string
    {
        return $this->large
            ? 'badge--large px-3 text-sm leading-5'
            : 'px-2.5 text-xs leading-4';
    }

    private function badgeVariant(): string
    {
        /*
         * We are explicitly defining the badge variants to avoid them getting purged from tailwind.
         *
         * Note: any new variant colors should be updated in the tailwind preset file.
         */
        return match($this->variant) {
            'blue-gray' => 'badge--blue-gray',
            'cool-gray' => 'badge--cool-gray',
            'blue' => 'badge--blue',
            'red' => 'badge--red',
            'rose' => 'badge--rose',
            'green' => 'badge--green',
            'orange' => 'badge--orange',
            'indigo' => 'badge--indigo',
            'pink' => 'badge--pink',
            'yellow' => 'badge--yellow',

            default => 'badge--gray',
        };
    }
}
