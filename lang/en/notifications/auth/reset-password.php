<?php

declare(strict_types=1);

return [
    'subject' => 'Reset your :domain password',
    'greeting' => 'Hello,',
    'intro-lines' => [
        'My system received a request to reset the password for the account associated with :email. No changes have been made to your account yet.',
        'You can reset your password by clicking the link below.',
    ],
    'outro-lines' => [
        'If you did not initiate this request, you can ignore this email or let me know at :support.',
        "Please note that your password will not change unless you click the link above and create a new one. If you don't use this link within :expiration, it will expire. [Click here to get a new password reset link](:request_url).",
        "If you've requested multiple reset emails, please make sure you click the link inside the most recent email.",
    ],
    'action' => 'Reset your password',

    'invalid-user' => [
        'subject' => "The email you provided doesn't match our records.",
        'greeting' => 'Hello,',
        'intro-lines' => [
            'My system received a request to reset your password on :domain.',
            'However, there is not an account associated with your email address (:email). Are you sure this is the email address for your account?',
            'Try logging in with another email or contact me to have an account created for you.',
        ],
        'outro-lines' => [
            'Still having trouble signing in? Please let me know at :support',
        ],
        'action' => 'Sign in with a different email',
    ],
];
