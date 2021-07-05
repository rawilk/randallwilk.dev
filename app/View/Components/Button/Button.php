<?php

declare(strict_types=1);

namespace App\View\Components\Button;

use App\View\Components\BladeComponent;
use Illuminate\Support\Str;

class Button extends BladeComponent
{
    public function __construct(
        public string $variant = 'secondary',
        public bool $block = false,
        public bool $icon = false,
        public string $containerClass = '',
        public bool $rounded = false,
        public null|string $size = 'md',
        public null|string $href = null,
        public $extraAttributes = '',
    ) {}

    public function buttonClass(): string
    {
        return collect([
            'relative',
            'button',
            $this->colorClass(),
            $this->block ? 'w-full button--block' : null,
            $this->icon ? 'button--icon' : null,
            $this->rounded ? 'rounded-full' : null,
            $this->buttonSizeClass(),
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

    private function buttonSizeClass(): null|string
    {
        return match($this->size) {
            'xs' => 'button--xs',
            'sm' => 'button--sm',
            'md' => 'button--md',
            'lg' => 'button--lg',
            'xl' => 'button--xl',
            default => null,
        };
    }

    /*
     * The color classes are all defined here to prevent tailwind from purging them.
     *
     * Note: Any new colors (variants) must be added to the tailwind-preset file.
     */

    private function colorClass(): string
    {
        if (Str::startsWith($this->variant, 'outline')) {
            return $this->outlineColorClass();
        }

        return match($this->variant) {
            'blue-gray' => 'button--blue-gray',
            'cool-gray' => 'button--cool-gray',
            'gray' => 'button--gray',
            'blue' => 'button--blue',
            'red' => 'button--red',
            'rose' => 'button--rose',
            'green' => 'button--green',
            'orange' => 'button--orange',
            'indigo' => 'button--indigo',
            'pink' => 'button--pink',
            'yellow' => 'button--yellow',
            'secondary' => 'button--secondary',
            default => 'button--white',
        };
    }

    private function outlineColorClass(): string
    {
        return match(Str::after($this->variant, 'outline-')) {
            'blue-gray' => 'button--outline-blue-gray',
            'cool-gray' => 'button--outline-cool-gray',
            'gray' => 'button--outline-gray',
            'blue' => 'button--outline-blue',
            'red' => 'button--outline-red',
            'rose' => 'button--outline-rose',
            'green' => 'button--outline-green',
            'orange' => 'button--outline-orange',
            'indigo' => 'button--outline-indigo',
            'pink' => 'button--outline-pink',
            'yellow' => 'button--outline-yellow',
            default => 'button--outline-outline-blue-gray',
        };
    }
}
