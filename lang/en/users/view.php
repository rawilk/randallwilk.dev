<?php

declare(strict_types=1);

return [
    'sections' => [
        'user_info' => [
            'heading' => 'Account information',
        ],

        'password' => [
            'heading' => 'Update password',
            'description' => 'Ensure the account is using a long, random password to stay secure.',
        ],

        'meta' => [
            'heading' => 'User meta',
        ],

        'actions' => [
            'heading' => 'Actions',
        ],
    ],

    'attributes' => [
        'id' => [
            'label' => 'ID',
        ],

        'h_key' => [
            'label' => 'Human key',
        ],
    ],

    'user_info_form' => [
        'pending_email' => [
            'change_pending' => 'User has a pending email change to **:email**. Please have them check their email for the link to confirm the new email address.',
        ],
    ],

    'password' => [
        'form' => [
            'password' => [
                'label' => 'New password',
            ],
        ],
    ],
];
