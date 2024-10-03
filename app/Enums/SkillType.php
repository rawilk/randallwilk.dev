<?php

declare(strict_types=1);

namespace App\Enums;

enum SkillType: string
{
    case TechStack = 'tech';
    case SkillStack = 'skill_stack';
    case Services = 'services';

    public function label(): string
    {
        return __("enums.skills.{$this->value}");
    }
}
