<?php

declare(strict_types=1);

return [
    'errors' => [

        'code_explanation_title' => 'What happened?',
        'instructions_title' => 'What can I do?',
        'visitor_title' => "If you're a site visitor",
        'owner_title' => "If you're the site owner",

        'retry_button' => 'Try this page again',
        'home_button' => 'Go home',
        'home_button_livewire' => 'Go back',

        '401' => [
            'title' => 'Unauthorized',
            'message' => "You don't have authorization for that on :app_name.",
            'code_explanation' => "A 401 error status indicates that you don't have valid credentials for the requested resource. In general, web servers and websites have directories and files that are not open to the public web for security reasons.",
            'visitor_instructions' => "Please use your browser's back button and check that you're in the right place. If you need assistance, please send us an email at :email.",
            'owner_instructions' => "Please check that you're in the right place and get in touch with your website provider if you believe this to be an error.",
        ],

        '403' => [
            'title' => 'Forbidden',
            'message' => "You don't have access permissions for that on :app_name.",
            'code_explanation' => "A 403 error status indicates that you don't have permission to access the file or page. In general, web servers and websites have directories and files that are not open to the public web for security reasons.",
            'visitor_instructions' => "Please use your browser's back button and check that you're in the right place. If you need assistance, please send us an email at :email.",
            'owner_instructions' => "Please check that you're in the right place and get in touch with your website provider if you believe this to be an error.",
        ],

        '404' => [
            'title' => 'Not Found',
            'message' => "We couldn't find what you're looking for on :app_name.",
            'code_explanation' => "A 404 error status implies that the file or page that you're looking for could not be found.",
            'visitor_instructions' => "Please use your browser's back button and check that you're in the right place. If you need assistance, please send us an email at :email.",
            'owner_instructions' => "Please check that you're in the right place and get in touch with your website provider if you believe this to be an error.",
        ],

        '500' => [
            'title' => 'Internal Server Error',
            'message' => 'The web server is returning an internal error for :app_name.',
            'code_explanation' => "A 500 error status implies there is a problem with the web server's software causing it to malfunction.",
            'visitor_instructions' => 'There is nothing you can do at the moment. If you need immediate assistance please send us an email at :email instead. We apologize for any inconvenience.',
            'owner_instructions' => 'This error can only be fixed by server admins, please contact your website provider.',
        ],

    ],
];
