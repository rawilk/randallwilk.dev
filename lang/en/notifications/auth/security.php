<?php

declare(strict_types=1);

return [
    'defaults' => [
        'subject' => 'Security alert',
        'check_account_button' => 'Check account',
        'reason_for_email' => 'You received this email to let you know about important changes to your :domain account and services.',
    ],

    'request_details' => [
        'label' => '**Request details**',
        'location' => 'Location: :location (approximate)',
        'ip' => 'IP address: :ip',
        'date' => 'Date: :date',
        'browser' => 'Browser: :browser',
        'platform' => 'Platform: :platform',
    ],

    'password_updated' => [
        'greeting' => 'Account password was updated',
        'line1' => "If you didn't initiate a change to your account's password, someone might be using your account. Check and secure your account now.",
        'line2' => 'Please contact me at :support if you need assistance securing your account.',
    ],

    'password_was_reset' => [
        'greeting' => 'Your password was reset',
        'line1' => 'No further action is required if this was you.',
        'line2' => "If you didn't reset your account password, someone might be using your account.",
        'line3' => 'Please contact me at :support if you need assistance securing your account.',
    ],

    'recovery_code_replaced' => [
        'greeting' => 'Recovery code was used for 2-Step Verification',
        'line1' => 'A recovery code was just used as a 2-Step Verification method for your account. If this was you, you can safely ignore this email.',
        'line2' => 'The code that was used has been invalidated and cannot be used again in the future. We have replaced the code with a new one, which can be viewed under the "Recovery Codes" section on your profile authentication settings.',
        'line3' => 'If you did not use a recovery code, someone might be using your account. If you need assistance securing your account, please contact me at :support.',
    ],

    'recovery_codes_regenerated' => [
        'greeting' => 'New backup codes generated for 2-Step Verification',
        'line1' => 'New recovery codes have been generated for your account. Be sure to record your new codes in a safe place, as your old codes will not work anymore.',
        'line2' => 'Keep your recovery codes in a **safe spot**. These codes are a last resort for accessing your account in case you lose your password and second factors. If you cannot find these codes, you **will** lose access to your account.',
        'line3' => 'To print or download your new codes, you can click on the link below to view your recovery codes.',
        'line4' => 'If you did not generate new recovery codes, someone might be using your account. If you need assistance securing your account, please contact me at :support.',
    ],

    'passkey_registered' => [
        'greeting' => 'Passkey added',
        'line1' => 'A new passkey labeled ":name" has just been added to your account. This passkey can be used as an alternative to your password to sign in to your account.',
        'line2' => 'To remove your passkey, visit [your account security settings](:url).',
        'line3' => 'If you did not initiate this action or believe your account has been compromised, someone might be using your account. Check and secure your account now.',
    ],

    'passkey_deleted' => [
        'greeting' => 'Passkey removed',
        'line1' => 'A passkey ":name" was removed from your account.',
        'line2' => 'You may still need to remove it from your password manager to remove it from autofill suggestions.',
        'line3' => 'If you did not initiate this action or believe your account has been compromised, someone might be using your account. Check and secure your account now.',
    ],

    'two_factor_app_added' => [
        'greeting' => 'Authenticator app added for 2-Step Verification',
        'line1' => "If you didn't register an authenticator app, someone might be using your account. Check and secure your account now.",
    ],

    'two_factor_app_removed' => [
        'greeting' => 'Authenticator app removed from 2-Step Verification',
        'line1' => 'The authenticator app labeled ":name" has been removed from your account and will no longer be able to be used as a second form of authentication.',
        'line2' => "If you didn't remove this authenticator app, someone might be using your account. Check and secure  your account now.",
    ],

    'webauthn_key_registered' => [
        'greeting' => 'Security key added for 2-Step Verification',
        'line1' => 'A security key labeled ":name" has been added to your account and can be used as a second factor of authentication for your account.',
        'line2' => 'If you did not register a security key, someone might be using your account. Check and secure your account now.',
    ],

    'webauthn_key_deleted' => [
        'greeting' => 'Security key removed from 2-Step Verification',
        'line1' => 'The security key labeled ":name" has been removed from your account and will no longer be able to be used as a second form of authentication.',
        'line2' => "If you didn't remove this security key, someone might be using your account. Check and secure your account now.",
    ],

    'two_factor_enabled' => [
        'greeting' => '2-Step Verification enabled on your account',
        'line1' => '2-Step Verification is an additional layer of security designed to ensure that you are the only person who can access your account. This helps protect your account on :domain.',
        'line2' => "You will be prompted for a second form of verification, either through an authenticator app or security key, each time you sign in. In the event you don't have access to your phone or security key, you may also print out a set of one-time codes that can be used to sign in.",
        'line3' => 'If you did not enable 2-Step Verification and believe that an unauthorized person has access to your account, please contact me at :support.',
    ],

    'two_factor_disabled' => [
        'greeting' => '2-Step Verification was disabled on your account',
        'line1' => '2-Step Verification has been disabled on your account, and will no longer be required to sign in to your account.',
        'line2' => 'Please note, I highly recommend having at least one method of 2-Step Verification enabled on your account for security.',
        'line3' => 'If you did not disable 2-Step Verification and believe an unauthorized person has access to your account, please contact me at :support.',
    ],

    'github_connected' => [
        'greeting' => 'GitHub account connected',
        'line1' => 'A GitHub account with the username ":username" has been connected to your user account. From now on you will have the option to sign in using your GitHub account.',
        'line2' => 'If you did not initiate this change, someone may be using your account. Check and secure your account now.',
    ],

    'github_disconnected' => [
        'greeting' => 'GitHub account was disconnected',
        'line1' => 'The connection to your GitHub account has been removed, and you will no longer be able to login using GitHub.',
        'line2' => 'If you did not initiate this change, someone may be using your account. Check and secure your account now.',
    ],
];
