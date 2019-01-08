<?php

namespace App\Enums\Requests;

use BenSampo\Enum\Enum;

final class ResponseStatus extends Enum
{
    /**
     * The response status for a request that resulted in an error.
     *
     * @var string
     */
    const ERROR = 'error';

    /**
     * The response status for a successful request.
     *
     * @var string
     */
    const SUCCESS = 'success';
}
