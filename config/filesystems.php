<?php

declare(strict_types=1);

return [
    'disks' => [

        'avatars' => match (env('STORAGE_DRIVER', 'local')) {
            's3' => [
                'driver' => 's3',
                'root' => 'avatars',
                'visibility' => 'public',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET'),
                'url' => env('AWS_URL'),
                'endpoint' => env('AWS_ENDPOINT'),
            ],
            default => [
                'driver' => 'local',
                'root' => storage_path('app/avatars'),
                'url' => env('APP_URL') . '/avatars',
                'visibility' => 'public',
            ],
        },

        'snapshots' => [
            'driver' => 'local',
            'root' => database_path('snapshots'),
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('avatars') => storage_path('app/avatars'),
    ],
];
