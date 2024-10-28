<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RepositoryType: string implements HasColor, HasLabel
{
    case Package = 'package';
    case Project = 'project';

    public function getLabel(): ?string
    {
        return __("enums/repository-type.{$this->value}.label");
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Package => 'success',
            self::Project => 'primary',
        };
    }
}
