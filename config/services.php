<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'github' => [
        'username' => env('GITHUB_USERNAME'),
        'token' => env('GITHUB_TOKEN'),
        'docs_access_token' => env('GITHUB_ACCESS_TOKEN'),
        'webhook_secret' => env('GITHUB_WEBHOOK_SECRET'),
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CALLBACK_URL'),
        'site_repo' => env('GITHUB_SITE_REPO'),
    ],

    'horizon' => [
        'email' => env('HORIZON_EMAIL'),
    ],

    'google' => [
        'analytics' => [
            'id' => env('GOOGLE_ANALYTICS_ID'),
        ],
    ],

    'algolia' => [
        'app_id' => env('ALGOLIA_APP_ID'),
    ],
];
