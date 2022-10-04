<?php

declare(strict_types=1);

namespace App\Enums;

use Rawilk\LaravelBase\Contracts\Enums\HasLabel;

enum SkillsEnum: string implements HasLabel
{
    case TECH_STACK = 'tech';
    case SKILL_STACK = 'skill_stack';
    case SERVICES = 'services';

    public function label(): string
    {
        return __("enums.skills.{$this->value}");
    }
}
