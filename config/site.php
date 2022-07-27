<?php

return [
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    'auth_bg_image' => 'https://images.unsplash.com/photo-1483354483454-4cd359948304?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=3000&q=80',

    /*
     * Primary menu link classes...
     */
    'main_menu' => [
        'item_base_class' => 'group border-l-4 py-2 px-3 flex items-center text-sm font-medium focus:outline-slate',
        'item_active_class' => 'bg-blue-100 border-blue-600 text-blue-600',
        'item_inactive_class' => 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-gray-200',
        'icon_base_class' => 'mr-3 h-6 w-6',
        'icon_active_class' => 'text-blue-500',
        'icon_inactive_class' => 'text-slate-400 group-hover:text-slate-500',
        'submenu_item_class' => 'py-2 pl-14 -ml-1 pr-3 flex items-center text-xs font-medium focus:outline-slate',
    ],
];
