<?php

return [
    'contact' => [
        'email' => 'randall@randallwilk.dev',

        'social' => [
            'Github' => 'https://github.com/rawilk',
            'Linkedin' => 'https://www.linkedin.com/in/randall-wilk',
            'Twitter' => 'https://twitter.com/wilkrandall',
        ],
    ],

    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),

    'timezone' => 'America/Chicago',

    /*
     * Primary menu link classes...
     */
    'main_menu' => [
        'item_base_class' => 'group border-l-4 py-2 px-3 flex items-center text-sm font-medium focus:outline-blue-gray',
        'item_active_class' => 'bg-blue-100 border-blue-600 text-blue-600',
        'item_inactive_class' => 'border-transparent text-blue-gray-600 hover:text-blue-gray-900 hover:bg-gray-200',
        'icon_base_class' => 'mr-3 h-6 w-6',
        'icon_active_class' => 'text-primary-500',
        'icon_inactive_class' => 'text-blue-gray-400 group-hover:text-blue-gray-500',
        'submenu_item_class' => 'py-2 pl-14 -ml-1 pr-3 flex items-center text-xs font-medium focus:outline-blue-gray',
    ],
];
