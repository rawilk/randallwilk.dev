<?php

declare(strict_types=1);

return [
    'providers' => [
        'users' => [
            'driver' => 'customEloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
    ],

    'timebox_duration' => 200_000,
];
