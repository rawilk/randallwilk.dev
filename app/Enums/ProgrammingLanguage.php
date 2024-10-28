<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProgrammingLanguage: string implements HasColor, HasLabel
{
    case Blade = 'Blade';
    case JavaScript = 'JavaScript';
    case Php = 'PHP';
    case Shell = 'Shell';
    case Unknown = 'Unknown';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::JavaScript => Color::Orange,
            self::Php => Color::Blue,
            default => 'gray',
        };
    }

    public function getLabel(): ?string
    {
        return __("enums/programming-language.{$this->value}.label");
    }
}
