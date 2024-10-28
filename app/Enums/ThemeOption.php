<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ThemeOption: string implements HasIcon, HasLabel
{
    case Light = 'light';
    case Dark = 'dark';
    case System = 'system';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Light => 'heroicon-m-sun',
            self::Dark => 'heroicon-m-moon',
            self::System => 'heroicon-m-computer-desktop',
        };
    }

    public function getLabel(): ?string
    {
        return __("enums/theme-select.{$this->value}.label");
    }
}
