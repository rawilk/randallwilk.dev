const mix = require('laravel-mix');
require('laravel-mix-tailwind');

mix
    .sass('resources/sass/front/app.scss', 'public/css')
    .tailwind('./tailwind.config.js')
    .disableNotifications()
    .version();
