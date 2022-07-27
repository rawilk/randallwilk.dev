<?php

declare(strict_types=1);

namespace App\Enums;

enum RepositoryTypeEnum: string
{
    case PACKAGE = 'package';
    case PROJECT = 'project';
}
