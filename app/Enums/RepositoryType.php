<?php

declare(strict_types=1);

namespace App\Enums;

enum RepositoryType: string
{
    case Package = 'package';
    case Project = 'project';

    public function bgColor(): string
    {
        return match ($this) {
            self::Package => 'bg-green-200',
            self::Project => 'bg-blue-200',
        };
    }

    public function label(): string
    {
        return __("enums.repository_type.{$this->value}");
    }
}
