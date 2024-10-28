<?php

declare(strict_types=1);

return [
    'channels' => [
        'docs' => [
            'driver' => 'daily',
            'days' => 14,
            'path' => storage_path('logs/docs/docs.log'),
        ],
    ],
];
