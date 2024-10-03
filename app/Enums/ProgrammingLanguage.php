<?php

declare(strict_types=1);

namespace App\Enums;

enum ProgrammingLanguage: string
{
    case Blade = 'Blade';
    case JavaScript = 'JavaScript';
    case Php = 'PHP';
    case Shell = 'Shell';
    case Unknown = 'Unknown';

    public function color(): string
    {
        return match ($this) {
            self::JavaScript => 'orange',
            self::Php => 'blue',
            default => 'gray',
        };
    }
}
