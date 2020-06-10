<?php

return [
    'googleAnalyticsId' => env('GOOGLE_ANALYTICS_ID'),

    /**
     * Contact info
     */
    'contact' => [
        'email' => 'randall@randallwilk.dev',
        'social' => [
            'Linkedin' => 'https://www.linkedin.com/in/randall-wilk',
            'Github' => 'https://github.com/rawilk',
            'Facebook' => 'https://www.facebook.com/randall.j.wilk',
        ],
        'twitter' => [
            'handle' => 'wilkrandall',
        ]
    ],

    /**
     * Projects
     */
    'projects' => [
        'openSource' => [
            [
                'name' => 'vue-context',
                'url' => 'https://github.com/rawilk/vue-context',
            ],
            [
                'name' => 'Laravel Modules',
                'url' => 'https://github.com/rawilk/laravel-modules',
            ],
            [
                'name' => 'Server Setup Scripts',
                'url' => 'https://github.com/rawilk/new-server-setup',
            ],
        ],
    ],
];