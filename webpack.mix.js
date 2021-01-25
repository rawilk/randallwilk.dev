const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix
    .js('resources/js/app.js', 'public/js')

    .sass('resources/sass/front/app.scss', 'public/css/app.css', {}, [
        tailwindcss('./tailwind.config.js')
    ])

    .sass('resources/sass/admin/app.scss', 'public/css/admin.css', {}, [
        tailwindcss('./tailwind-admin.config.js')
    ])

    .sourceMaps(false)
    .disableSuccessNotifications()
    .version();
