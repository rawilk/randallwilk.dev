<?php

declare(strict_types=1);

namespace App\View\Components\Navigation;

use App\View\Components\BladeComponent;

final class InnerNavItem extends BladeComponent
{
    public bool $active;

    public function __construct(
        public string $href = '#',
        public $icon = false,
        null|bool $active = null,
    ) {
        $this->active = is_null($active)
            ? request()->fullUrlIs($href)
            : $active;
    }

    public function linkClass(): string
    {
        return collect([
            'group space-x-3 rounded-md px-3 py-2 flex items-center text-sm leading-5 font-medium focus:outline-blue-gray transition-colors',
            $this->active ? 'text-blue-500 bg-blue-gray-200' : 'text-cool-gray-600 hover:text-cool-gray-600 hover:bg-blue-gray-200 focus:text-cool-gray-600 focus:bg-blue-gray-200',
        ])
        ->filter()
        ->implode(' ');
    }

    public function iconClass(): string
    {
        return collect([
            'flex-shrink-0 h-6 w-6 transition-colors',
            $this->active ? 'text-blue-500' : 'text-cool-gray-400 group-hover:text-cool-gray-500 group-focus:text-cool-gray-500',
        ])
        ->filter()
        ->implode(' ');
    }
}
