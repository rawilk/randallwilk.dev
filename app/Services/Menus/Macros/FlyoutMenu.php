<?php

declare(strict_types=1);

namespace App\Services\Menus\Macros;

use Spatie\Menu\Laravel\Menu;

final class FlyoutMenu implements MenuMacro
{
    public function register(): void
    {
        Menu::macro('flyout', function (?callable $callback = null, ?callable $footerCallback = null) {
            /** @var Menu $this */
            $this->wrap('div', [
                'x-data' => '{ open: false }',
                'x-on:click.outside' => 'open = false',
                'x-on:keydown.esc.window' => 'open = false',
            ])
                ->withoutParentTag()
                ->setWrapperTag('div')
                ->addClass('absolute z-10 -ml-4 mt-3 w-screen max-w-md transform lg:max-w-2xl lg:left-1/2 lg:ml-0 lg:-translate-x-1/2')
                ->setAttribute('x-show', 'open')
                ->setAttribute('x-cloak', '')
                ->setAttribute('x-transition:enter', 'transition ease-out duration-200')
                ->setAttribute('x-transition:enter-start', 'opacity-0 translate-y-1')
                ->setAttribute('x-transition:enter-end', 'opacity-100 translate-y-0')
                ->setAttribute('x-transition:leave', 'transition ease-in duration-150')
                ->setAttribute('x-transition:leave-start', 'opacity-100 translate-y-0')
                ->setAttribute('x-transition:leave-end', 'opacity-0 translate-y-1')
                ->setActiveClassOnLink();

            $this->submenu('', function (Menu $menu) use ($callback, $footerCallback) {
                $menu->setWrapperTag('div')
                    ->withoutParentTag()
                    ->addClass('overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5');

                $menu->submenu('', function (Menu $menu) use ($callback) {
                    $menu->setWrapperTag('div')
                        ->withoutParentTag()
                        ->addClass('relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8');

                    is_callable($callback) && $callback($menu);
                });

                if (is_callable($footerCallback)) {
                    $menu->submenu('', function (Menu $menu) use ($footerCallback) {
                        $menu->setWrapperTag('div')
                            ->withoutParentTag()
                            ->addClass('bg-gray-50 p-5 sm:p-8')
                            ->addItemClass('-m-3 flow-root rounded-md p-3 hover:bg-gray-100');

                        $footerCallback($menu);
                    });
                }
            });
        });
    }
}
