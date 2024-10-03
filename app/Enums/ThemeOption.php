<?php

declare(strict_types=1);

namespace App\Enums;

enum ThemeOption: string
{
    case Light = 'light';
    case Dark = 'dark';
    case System = 'system';

    public function label(): string
    {
        return __("enums.theme_select.{$this->value}");
    }

    public function icon(): string
    {
        return match ($this) {
            self::Light => 'theme.light',
            self::Dark => 'theme.dark',
            self::System => 'theme.system',
        };
    }
}
