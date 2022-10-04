<?php

declare(strict_types=1);

namespace App\Enums;

use Rawilk\LaravelBase\Contracts\Enums\HasLabel;

enum ThemeSelectEnum: string implements HasLabel
{
    case LIGHT = 'light';
    case DARK = 'dark';
    case SYSTEM = 'system';

    public function label(): string
    {
        return __("enums.theme_select.{$this->value}");
    }

    public function icon(): string
    {
        return match ($this) {
            self::LIGHT => 'theme.light',
            self::DARK => 'theme.dark',
            self::SYSTEM => 'theme.system',
        };
    }
}
