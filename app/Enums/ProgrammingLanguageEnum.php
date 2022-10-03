<?php

declare(strict_types=1);

namespace App\Enums;

enum ProgrammingLanguageEnum: string
{
    case JAVASCRIPT = 'JavaScript';
    case PHP = 'PHP';
    case SHELL = 'Shell';

    public function color(): string
    {
        return match ($this) {
            self::JAVASCRIPT => 'orange',
            self::PHP => 'blue',
            default => 'gray',
        };
    }
}
