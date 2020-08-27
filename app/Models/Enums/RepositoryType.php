<?php

namespace App\Models\Enums;

use MyCLabs\Enum\Enum;

class RepositoryType extends Enum
{
    /** @var string */
    public const PACKAGE = 'package';

    /** @var string */
    public const PROJECT = 'project';
}
