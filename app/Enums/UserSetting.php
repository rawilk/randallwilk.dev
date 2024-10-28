<?php

declare(strict_types=1);

namespace App\Enums;

enum UserSetting: string
{
    case PreferredMfaMethod = 'mfa.preference';
}
