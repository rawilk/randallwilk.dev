<?php

declare(strict_types=1);

namespace App\Services\Menus\Macros;

class MenuMacroRegistrar
{
    /**
     * @var <int, class-string<\App\Services\Menus\Macros\MenuMacro>>
     */
    protected const array MACROS = [
        FlyoutMenu::class,
    ];

    public static function register(): void
    {
        foreach (static::MACROS as $macro) {
            (new $macro)->register();
        }
    }
}
