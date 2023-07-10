<?php

return [
    'abilities' => ['page_title' => 'User Abilities'],
    'delete_modal_text' => 'This will permanently delete **:name**! You will not be able to recover any data associated with this user.',
    'delete_modal_title' => 'Delete User',
    'form' => [
        'labels' => [
            'email' => 'Email address',
            'name' => 'Name',
            'password' => 'Password',
            'timezone' => 'Timezone',
        ],
    ],
    'labels' => [
        'account_info_subtitle' => "Configure the user's account.",
        'account_info_title' => 'Account Information',
        'avatar' => 'Photo',
        'cancel_avatar_upload' => 'Cancel Upload',
        'profile_info_title' => 'Profile Information',
        'profile_info_update_subtitle' => "Update the user account's profile information and email address.",
        'remove_avatar' => 'Remove Photo',
        'select_new_avatar' => 'Select a New Photo',
        'update_password_subtitle' => 'Ensure the account is using a long, random password to stay secure.',
        'update_password_title' => 'Update Password',
        'user_abilities_subtitle' => 'Define what the user is allowed to do in the application.',
        'user_abilities_title' => 'User Abilities',
    ],
    'profile' => [
        'account' => [
            'page_title' => 'My Account',
            'profile_info_subtitle' => "Update your account's profile information and email address.",
            'profile_info_title' => 'Profile Information',
        ],
        'alerts' => ['github_disconnected' => 'Disconnected from GitHub account successfully.'],
        'authentication' => [
            'title' => 'Authentication Settings',
            'update_password_subtitle' => 'Ensure your account is using a long, random password to stay secure.',
            'update_password_title' => 'Update Password',
        ],
        'delete' => [
            'confirmation_text' => 'Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
            'title' => 'Delete Account',
            'trigger' => 'Delete account',
            'warning' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Please be sure this is what you want to do.',
        ],
        'form' => [
            'labels' => [
                'confirm_password' => 'Confirm password',
                'current_password' => 'Current password',
                'new_password' => 'New password',
            ],
        ],
        'page_title' => 'My Profile',
        'social_auth' => [
            'connect_button' => 'Connect to :provider account',
            'connected_account' => 'Connected account: :username',
            'disconnect_button' => 'Disconnect from :provider',
            'github_desc' => 'Sign in without a password using your GitHub account.',
            'github_title' => 'GitHub',
            'subtitle' => 'Connect to a social account to sign in with it.',
            'title' => 'Social Authentication',
        ],
        'tabs' => ['account' => 'Account Information', 'authentication' => 'Authentication'],
    ],
    'singular' => 'user',
];
