<?php

declare(strict_types=1);

namespace App\View\Components\Navigation;

use App\View\Components\BladeComponent;

final class InnerNav extends BladeComponent
{
    public function __construct(
        public null|string $nav = '',
        public bool $stickyNav = true,
    ) {}

    public function asideClass(): string
    {
        return collect([
            'lg:col-span-3',
            'lg:px-0',
            'lg:py-0',
            'py-6',
            $this->stickyNav ? 'px-4' : 'px-2 sm:px-6',
        ])->filter()->implode(' ');
    }

    public function navClass(): string
    {
        return collect([
            'space-y-1',
            $this->stickyNav ? 'md:sticky md:top-2' : null,
        ])->filter()->implode(' ');
    }

    public function contentClass(): string
    {
        return collect([
            'lg:col-span-9',
            'space-y-6',
            'lg:px-0',
            $this->stickyNav ? null : 'sm:px-6',
        ])->filter()->implode(' ');
    }
}
