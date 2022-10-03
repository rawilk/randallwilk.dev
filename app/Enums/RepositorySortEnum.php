<?php

declare(strict_types=1);

namespace App\Enums;

use Rawilk\LaravelBase\Contracts\Enums\HasLabel;

enum RepositorySortEnum: string implements HasLabel
{
    case DOWNLOADS = 'downloads';
    case NAME = 'name';
    case STARS = 'stars';
    case REPOSITORY_CREATED_AT = 'repository_created_at';

    public function shouldBeDesc(): bool
    {
        return $this !== self::NAME;
    }

    public function selectValue(): string
    {
        if ($this->shouldBeDesc()) {
            return "-{$this->value}";
        }

        return $this->value;
    }

    public function label(): string
    {
        return __("enums.repository_sort.{$this->value}");
    }
}
