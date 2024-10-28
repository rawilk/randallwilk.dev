<?php

declare(strict_types=1);

return [
    'subject' => 'Reset your :domain password',
    'greeting' => 'Hello,',
    'intro' => 'My system received a request to reset the password for the account associated with :email. No changes have been made to your account yet.',
    'link_instructions' => 'You can reset your password by clicking the link below.',
    'button' => 'Reset your password',
    'line3' => 'If you did not initiate this request, you can ignore this email or let me know at :support.',
    'expire_info' => 'Please note that your password will not change unless you click the link above and create a new one. **This link will expire in :count minutes**. If your link has expired, you can always [request another](:request_url).',
    'multiple_requests_notice' => "If you've requested multiple reset emails, please make sure you click the link inside the most recent email.",

    'invalid_user' => [
        'subject' => "The email you provided doesn't match our records.",
        'greeting' => 'Hello,',
        'intro' => 'My system received a request to reset your password on :domain.',
        'line2' => 'However, there is not an account associated with your email address (:email). Are you sure this is the email address for your account?',
        'login_instructions' => 'Try logging in with another email or contact me to have an account created for you.',
        'login_button' => 'Sign in with a different email',
        'help_info' => 'Still having trouble signing in? Please let me know at :support',
    ],
];
