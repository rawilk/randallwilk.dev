<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;

class DateColumn extends TextColumn
{
    public static string $defaultDateDisplayFormat = 'M. d, Y';

    public static string $defaultDateTimeDisplayFormat = 'M. d, Y g:i a';

    public static string $defaultTimeDisplayFormat = 'g:i a';

    public function date(string|Closure|null $format = null, ?string $timezone = null): static
    {
        $format ??= static::$defaultDateDisplayFormat;

        return parent::date($format, $timezone);
    }

    public function dateTime(string|Closure|null $format = null, ?string $timezone = null): static
    {
        $format ??= static::$defaultDateTimeDisplayFormat;

        return parent::dateTime($format, $timezone);
    }

    public function time(string|Closure|null $format = null, ?string $timezone = null): static
    {
        $format ??= static::$defaultTimeDisplayFormat;

        return parent::time($format, $timezone);
    }
}
