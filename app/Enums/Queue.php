<?php

declare(strict_types=1);

namespace App\Enums;

enum Queue: string
{
    case DefaultQueue = 'default';
    case Mail = 'mail';

    public static function supervisor1(): array
    {
        return array_column([
            self::DefaultQueue,
            self::Mail,
        ], 'value');
    }
}
