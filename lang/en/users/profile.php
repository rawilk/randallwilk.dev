<?php

declare(strict_types=1);

return [
    'timezone' => [
        'label' => 'Timezone',
        'local_time' => 'Local time: :time',
    ],

    'preferred_mfa' => [
        'title' => 'Preferred 2FA Method',
        'description' => 'Set your preferred method to use for two-factor authentication when signing in.',

        'form' => [
            'method' => [
                'options' => [
                    'app' => 'Authenticator app',
                    'webauthn' => 'Security keys',
                    'webauthn_or_passkey' => 'Passkeys or security keys',
                ],
            ],

            'success' => 'Your preferred 2FA method was updated!',
        ],
    ],

    'connected_accounts' => [
        'heading' => 'Connected accounts',
        'connected_account' => 'Connected account: **:username**',

        'github' => [
            'heading' => 'GitHub',
            'placeholder' => 'Sign in with your GitHub account.',
        ],

        'actions' => [
            'connect' => [
                'label' => 'Connect account',
                'success' => 'Your account has been connected successfully.',
            ],

            'disconnect' => [
                'label' => 'Disconnect',
                'success' => 'Disconnected.',
            ],
        ],
    ],
];
