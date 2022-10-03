<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * This enum is used for defining our application's queue names.
 */
enum QueuesEnum: string
{
    case DEFAULT_QUEUE = 'default';
    case MAIL = 'mail';
}
