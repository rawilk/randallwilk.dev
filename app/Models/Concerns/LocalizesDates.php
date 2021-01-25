<?php

namespace App\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait LocalizesDates
{
    public function getCreatedAtForHumansAttribute(): ?string
    {
        if (! ($date = $this->{$this->getCreatedAtColumn()})) {
            return null;
        }

        return $this->getDateTimeForHumans($date);
    }

    public function getUpdatedAtForHumansAttribute(): ?string
    {
        if (! ($date = $this->{$this->getUpdatedAtColumn()})) {
            return null;
        }

        return $this->getDateTimeForHumans($date);
    }

    protected function getDateTimeForHumans(?Carbon $date): ?string
    {
        if (! $date) {
            return null;
        }

        return $date->tz(userTimezone())->format($this->dateTimeFormat());
    }

    protected function dateTimeFormat(): string
    {
        return property_exists($this, 'dateTimeFormat')
            ? $this->dateTimeFormat
            : 'M. d, Y g:i a';
    }

    public function __get($key)
    {
        if ($this->isForHumansAttribute($key)) {
            return $this->getDateTimeForHumans(
                $this->getAttribute(Str::beforeLast($key, '_for_humans'))
            );
        }

        return parent::__get($key);
    }

    public function isForHumansAttribute(string $key): bool
    {
        $methodName = 'get' . Str::studly($key) . 'Attribute';
        if (method_exists($this, $methodName)) {
            return false;
        }

        return Str::endsWith($key, '_for_humans')
            && $this->getAttribute(Str::beforeLast($key, '_for_humans'))
            && $this->isDateAttribute(Str::beforeLast($key, '_for_humans'));
    }
}
