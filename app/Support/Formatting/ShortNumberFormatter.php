<?php

declare(strict_types=1);

namespace App\Support\Formatting;

use NumberFormatter;

final class ShortNumberFormatter
{
    private int $maxDecimalPlaces = 1;

    private string $locale = 'en_us';

    private NumberFormatter $formatter;

    public function __construct(private null|int|float $value)
    {
    }

    public static function of(null|int|float $value): self
    {
        return new self($value);
    }

    public function usingLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function maxDecimalPlaces(int $max): self
    {
        $this->maxDecimalPlaces = $max;

        return $this;
    }

    public function format(): string
    {
        if (is_null($this->value)) {
            return '';
        }

        $this->initializeFormatter();

        return $this->formatter->format($this->value);
    }

    private function initializeFormatter(): void
    {
        $this->formatter = new NumberFormatter($this->locale, NumberFormatter::PADDING_POSITION);

        if ($this->value > 1000) {
            $this->formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $this->maxDecimalPlaces);
        }
    }
}
