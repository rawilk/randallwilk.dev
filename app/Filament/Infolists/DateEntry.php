<?php

declare(strict_types=1);

namespace App\Filament\Infolists;

use Filament\Infolists\Components\TextEntry;

class DateEntry extends TextEntry
{
    public static string $defaultDateDisplayFormat = 'M. d, Y';

    public static string $defaultDateTimeDisplayFormat = 'M. d, Y g:i a';

    public static string $defaultTimeDisplayFormat = 'g:i a';

    public function date(?string $format = null, ?string $timezone = null): static
    {
        $format ??= static::$defaultDateDisplayFormat;

        return parent::date($format, $timezone);
    }

    public function dateTime(?string $format = null, ?string $timezone = null): static
    {
        $format ??= static::$defaultDateTimeDisplayFormat;

        return parent::dateTime($format, $timezone);
    }

    public function time(?string $format = null, ?string $timezone = null): static
    {
        $format ??= static::$defaultTimeDisplayFormat;

        return parent::time($format, $timezone);
    }
}
