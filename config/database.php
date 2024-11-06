<?php

declare(strict_types=1);

return [
    'connections' => [
        'staging' => [
            'driver' => 'pgsql',
            'url' => env('STAGING_DB_URL'),
            'host' => env('STAGING_DB_HOST', '127.0.0.1'),
            'port' => env('STAGING_DB_PORT', '5432'),
            'database' => env('STAGING_DB_DATABASE', 'laravel'),
            'username' => env('STAGING_DB_USERNAME', 'root'),
            'password' => env('STAGING_DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],
    ],
];
