<?php

return [
    '401' => [
        'description' => "Seems like you don't have access to this page.",
        'title' => 'Unauthorized',
    ],
    '403' => [
        'description' => "It appears you don't have the right clearance to do this...",
        'title' => 'Forbidden',
    ],
    '404' => [
        'description' => 'The resource you are looking for could have been removed, had its name changed or is temporarily unavailable.',
        'title' => 'Page not found',
    ],
    '500' => [
        'description' => "My server seems to have a little trouble building this page...<br>I'll get to the bottom of this asap!",
        'title' => 'Server error',
    ],
    '503' => [
        'contact_title' => 'Even though my site is undergoing some maintenance, <br>you can still reach me.',
        'description' => "I'm currently working on some improvements to the site...<br>Please check back in a couple of minutes!",
        'slogan' => 'Be right back!',
        'title' => 'Scheduled Maintenance',
    ],
    'contact_title' => 'If you still have questions, please contact me so I can help you out.',
    'suggestions' => [
        'contact' => 'Contact',
        'docs' => 'Docs',
        'home' => 'Home page',
        'links_title' => 'Here are some helpful links instead:',
        'open_source' => 'Open source packages',
        'privacy' => 'Privacy policy',
    ],
];
