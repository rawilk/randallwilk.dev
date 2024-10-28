<?php

declare(strict_types=1);

return [
    'heading' => 'Forgotten your password?',
    'subheading' => "Enter the email address you used when you joined and we'll send you instructions to reset your password.",

    'actions' => [
        'login' => [
            'label' => 'Back to login',
        ],

        'resend' => [
            'label' => 'Resend link',
        ],

        'submit' => [
            'label' => 'Send password reset link',
        ],
    ],

    'alerts' => [
        'sent' => [
            'title' => 'Check your email',
            'description' => "A password reset link was sent to :email. Please click the link in that email to reset your password.\n\nIf you do not receive the password reset email within a few moments, please check your spam folder or other filtering tools.",
        ],
    ],
];
