<?php

declare(strict_types=1);

namespace App\Services\Menus\Macros;

use Spatie\Menu\Laravel\Menu;

final class ExpandableMenu implements MenuMacro
{
    public function register(): void
    {
        Menu::macro('expandable', function (null | callable $callback = null) {
            /** @var \Spatie\Menu\Laravel\Menu $this */
            $this
                ->wrap('div', [
                    'class' => 'submenu space-y-1',
                    'x-bind:class' => "{ 'submenu--open': open }",
                    'x-data' => '{ open: false, hasActiveChild: false }',
                    'x-bind:aria-expanded' => 'JSON.stringify(open)',
                    'x-cloak' => '',
                    'x-init' => "
                        open = Array.from(\$el.children)
                            .filter(child => child.classList.contains('menu-item--active'))
                            .length > 0;

                        hasActiveChild = open;
                    ",
                ])
                ->withoutParentTag()
                ->withoutWrapperTag()
                ->setActiveClassOnLink()
                ->setActiveClass('menu-item--active')
                ->addItemClass('menu-item');

            is_callable($callback) && $callback($this);
        });
    }
}
