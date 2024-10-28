<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RepositorySort: string implements HasLabel
{
    case Downloads = 'downloads';
    case Name = 'name';
    case Stars = 'stars';
    case RepositoryCreatedAt = 'repository_created_at';

    public function shouldBeDesc(): bool
    {
        return $this !== self::Name;
    }

    public function selectValue(): string
    {
        if ($this->shouldBeDesc()) {
            return "-{$this->value}";
        }

        return $this->value;
    }

    public function getLabel(): ?string
    {
        return __("enums/repository-sort.{$this->value}.label");
    }
}
