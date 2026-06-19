<?php

declare(strict_types=1);

return [
    'mailers' => [
        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'resend',
            ],
            'retry_after' => 60,
        ],
    ],
];
