const mix = require('laravel-mix');
require('laravel-mix-tailwind');

mix
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/front/app.scss', 'public/css')
    .tailwind('./tailwind.config.js')
    .disableNotifications()
    .version();
