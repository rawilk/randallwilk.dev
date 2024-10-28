<?php

declare(strict_types=1);

return [
    'navigation_label' => 'Users',

    'model' => [
        'singular' => 'user',
        'plural' => 'users',
    ],

    'table' => [
        'name' => [
            'label' => 'Name',
        ],

        'email' => [
            'label' => 'Email',
        ],

        'timezone' => [
            'label' => 'Timezone',
        ],

        'is_admin' => [
            'label' => 'Is admin',
        ],
    ],

    'form' => [
        'name' => [
            'label' => 'Full name',
            'placeholder' => 'Tom Cooke',
        ],

        'email' => [
            'label' => 'Email address',
            'placeholder' => 'email@example.com',
        ],

        'timezone' => [
            'label' => 'Timezone',
        ],

        'is_admin' => [
            'label' => 'Is administrator',
            'help' => 'Check to give user access to the admin panel.',
        ],

        'avatar' => [
            'label' => 'Profile picture',
        ],
    ],

    'actions' => [
        'delete' => [
            'infolist_title' => 'Delete user',
            'infolist_description' => 'Permanently delete :first_name account and any data associated with the account.',
            'modal_description' => 'This will permanently delete **:name** from the site and delete any data associated with their account. This action cannot be reversed.',
        ],

        'delete_bulk' => [
            'modal_description' => 'This will permanently delete the selected users and all of their data from the site. This action cannot be reversed.',
        ],

        'avatar' => [
            'label' => 'Profile picture',
            'alt' => ':name profile picture',

            'edit' => [
                'trigger' => 'Edit',
                'upload_trigger' => 'Upload a photo...',
                'input_label' => 'Photo',
                'helper_text' => 'Max photo size of 1 MB is allowed.',
                'modal_title' => 'Update profile picture',
                'submit_button' => 'Save',
                'success' => 'Your profile picture has been updated.',
                'success_other_user' => 'The profile picture has been updated.',
            ],

            'remove' => [
                'trigger' => 'Remove photo',
                'success' => 'Your profile picture has been removed.',
                'success_other_user' => 'The profile picture has been removed.',
            ],
        ],
    ],
];
