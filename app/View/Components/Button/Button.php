<?php

declare(strict_types=1);

namespace App\View\Components\Button;

use App\View\Components\BladeComponent;

class Button extends BladeComponent
{
    public function __construct(
        public string $variant = 'secondary',
        public bool $block = false,
        public bool $icon = false,
        public string $containerClass = '',
        public bool $rounded = false,
        public null|string $size = 'md',
        public null|string $href = null
    ) {}

    public function buttonClass(): string
    {
        return collect([
            'relative',
            'button',
            $this->variant ? "button--{$this->variant}" : null,
            $this->block ? 'w-full button--block' : null,
            $this->icon ? 'button--icon' : null,
            $this->rounded ? 'rounded-full' : null,
            $this->size ? "button--{$this->size}" : null,
        ])->filter()->implode(' ');
    }

    public function tag(): string
    {
        return $this->href ? 'a' : 'button';
    }

    public function containerClass(): string
    {
        return collect([
            'relative',
            'inline-flex',
            'button-container',
            $this->block ? 'w-full button--block' : null,
            $this->containerClass,
        ])->filter()->implode(' ');
    }
}
