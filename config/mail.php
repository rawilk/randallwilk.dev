<?php

declare(strict_types=1);

return [
    'mailers' => [
        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'resend',
                'mailgun',
            ],
            'retry_after' => 60,
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],
    ],
];
