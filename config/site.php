<?php

declare(strict_types=1);

return [
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    'auth_bg_image' => 'https://images.unsplash.com/photo-1483354483454-4cd359948304?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=3000&q=80',

    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),

    'contact' => [
        'email' => 'randall@randallwilk.dev',

        'social' => [
            'GitHub' => 'https://github.com/rawilk',
            'Linkedin' => 'https://www.linkedin.com/in/randall-wilk',
            'Twitter' => 'https://twitter.com/wilkrandall',
        ],
    ],

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

    'skills' => [
        'tech' => [
            'HTML' => [
                'url' => 'https://developer.mozilla.org/en-US/docs/Learn/Getting_started_with_the_web/HTML_basics',
                'description' => 'The code that structures a web page. Would I even be a web developer if I didn\'t know HTML?',
            ],
            'CSS' => [
                'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS',
                'description' => 'Another fundamental part of every web page; CSS is a critical skill to have for styling a website.',
            ],
            'JavaScript' => [
                'url' => 'https://developer.mozilla.org/en-US/docs/Web/JavaScript',
                'description' => 'The language that makes it possible to add interactivity to a web page. I know enough Vanilla JavaScript to do what I need to do on a site.',
            ],
            'PHP' => [
                'url' => 'https://www.php.net/',
                'description' => 'A popular general-purpose scripting language suited for web development. PHP is my primary programming language.',
            ],
            'Laravel' => [
                'url' => 'https://laravel.com',
                'description' => 'If I can use it and it makes sense for a project, I will choose Laravel. I honestly don\'t know if I would have the passion I have for web development today if it wasn\'t for Laravel.',
            ],
            'Tailwind CSS' => [
                'url' => 'https://tailwindcss.com',
                'description' => 'Before Tailwind, I used Bootstrap. The flexibility I get for styling from Tailwind is second-to-none to other CSS frameworks and I don\'t think I would ever use anything else ever again.',
            ],
            'Alpine.js' => [
                'url' => 'https://alpinejs.dev',
                'description' => 'Alpine.js was a complete game changer for me. With Alpine, I am able to sprinkle in interactivity into my pages much easier and nicer than when I was using Vue.',
            ],
            'Laravel Livewire' => [
                'url' => 'https://laravel-livewire.com',
                'description' => 'Another game changer for me, Laravel Livewire makes building interactive components almost trivial, and drastically reduces the amount of custom JavaScript I need to write in an application.',
            ],
            'MySQL' => [
                'url' => 'https://www.mysql.com',
                'description' => 'Almost every web app needs a database, and I prefer to use MySQL or occasionally MariaDB, which is a fork of MySQL.',
            ],
            'Pest PHP' => [
                'url' => 'https://pestphp.com',
                'description' => 'Pest is a testing framework with a focus on simplicity.',
            ],
            'Vite' => [
                'url' => 'https://vitejs.dev',
                'description' => 'Vite is a lightning fast front-end build tool and has become my preferred build tool in new projects.',
            ],
            'Webpack' => [
                'url' => 'https://webpack.js.org',
                'description' => 'Another front-end build tool I\'m skilled with. Even though I don\'t use it as much anymore, I still know my way around Webpack.',
            ],
            'WordPress' => [
                'url' => 'https://wordpress.com',
                'description' => 'I got my start with PHP using WordPress. If I need to, I know how to build custom themes and plugins for a WordPress site.',
            ],
        ],
        'skill_stack' => [
            'Object Oriented Programming' => [
                'description' => 'Most programming I do is OOP. Since Laravel heavily relies on OOP, I\'m very familiar with OOP concepts and the MVC design pattern.',
            ],
            'Functional Programming' => [
                'description' => 'Although I usually prefer OOP, functional programming still has its place, especially on the front-end with JavaScript.',
            ],
            'GIT' => [
                'url' => 'https://git-scm.com/',
                'description' => 'GIT (version control) is a critical skill I think every developer should have.',
            ],
            'Server Management' => [
                'description' => 'Although I usually use Laravel Forge for server provisioning and management now, I still know my way around a Linux server. Even with Forge, I still need to SSH into a server sometimes and run some commands manually.',
            ],
            'UI Design' => [
                'description' => 'Even though I\'m stronger as a back-end developer, I still have a good understanding on how to make a website or application look "good".',
            ],
            'UX Design' => [
                'description' => 'User experience design is another important skill to have. I have a good understanding on how to design a UI that is accessible and easy to use for end-users.',
            ],
            'Accessibility' => [
                'description' => 'Building accessible websites and applications is important to allow for a better experience for everyone. I may not know everything about web accessibility but I always challenge myself to make my works as accessible as I possibly can.',
            ],
            'Optimization' => [
                'description' => 'I always to try to optimize my builds as much as I can for a performant web application.',
            ],
            'Testing' => [
                'description' => 'Testing is an important skill I have for catching bugs in an application and just making sure everything works the way it is supposed to.',
            ],
        ],
        'services' => [
            'GitHub' => [
                'url' => 'https://github.com',
                'description' => 'GitHub is used as my development and version control platform. I feel that GitHub provides the best experience for contributors.',
            ],
            'Cloudflare' => [
                'url' => 'https://www.cloudflare.com',
                'description' => 'Cloudflare is amazing for speeding up a site using CDNs and helping secure it.',
            ],
            'Laravel Forge' => [
                'url' => 'https://forge.laravel.com',
                'description' => 'Forge takes care of the hard parts of provisioning and maintaining a server for me, and allows me to spend more time developing my applications.',
            ],
            'DigitalOcean' => [
                'url' => 'https://digitalocean.com',
                'description' => 'All my projects are hosted on DigitalOcean droplets. It\'s the best scalable hosting platform for me.',
            ],
            'Ray' => [
                'url' => 'https://myray.app',
                'description' => 'Ray makes debugging web apps much easier and enjoyable.',
            ],
        ],
    ],
];
