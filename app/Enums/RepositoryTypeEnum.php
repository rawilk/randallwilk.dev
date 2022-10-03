<?php

declare(strict_types=1);

namespace App\Enums;

use Rawilk\LaravelBase\Contracts\Enums\HasLabel;

enum RepositoryTypeEnum: string implements HasLabel
{
    case PACKAGE = 'package';
    case PROJECT = 'project';

    public function bgColor(): string
    {
        return match ($this) {
            self::PACKAGE => 'bg-green-200',
            self::PROJECT => 'bg-blue-200',
        };
    }

    public function label(): string
    {
        return __("enums.repository_type.{$this->value}");
    }
}
