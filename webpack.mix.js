const mix = require('laravel-mix');

mix
    // App scripts...
    .js('resources/js/app.js', 'js')

    // App CSS...
    .postCss('resources/css/app.css', 'css')
    .postCss('resources/css/front.css', 'css')

    .sourceMaps(false)
    .version()
    .disableSuccessNotifications()
    .options({
        postCss: [
            require('postcss-import'),
            require('tailwindcss'),
        ],
    });

// const tailwindcss = require('tailwindcss');
//
// mix
//     .js('resources/js/app.js', 'public/js')
//
//     .sass('resources/sass/front/app.scss', 'public/css/app.css', {}, [
//         tailwindcss('./tailwind.config.js')
//     ])
//
//     .sass('resources/sass/admin/app.scss', 'public/css/admin.css', {}, [
//         tailwindcss('./tailwind-admin.config.js')
//     ])
//
//     .sourceMaps(false)
//     .disableSuccessNotifications()
//     .version();
