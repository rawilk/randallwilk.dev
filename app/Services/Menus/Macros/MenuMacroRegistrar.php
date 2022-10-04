<?php

declare(strict_types=1);

namespace App\Services\Menus\Macros;

final class MenuMacroRegistrar
{
    /**
     * @var <int, class-string<\App\Services\Menus\Macros\MenuMacro>>
     */
    private const MACROS = [
        ExpandableMenu::class,
        FlyoutMenu::class,
    ];

    public static function register(): void
    {
        foreach (self::MACROS as $macro) {
            (new $macro)->register();
        }
    }
}
