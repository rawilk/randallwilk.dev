<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SkillType: string implements HasLabel
{
    case TechStack = 'tech';
    case SkillStack = 'skill_stack';
    case Services = 'services';

    public function getLabel(): ?string
    {
        return __("enums/skill-type.{$this->value}.label");
    }
}
