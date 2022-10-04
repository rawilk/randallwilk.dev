const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');
const safelist = require('./tailwind-safelist-preset');

module.exports = {
    content: [
        './app/**/*.php',
        './resources/**/*.html',
        './resources/**/*.js',
        './resources/**/*.php',
        './config/site.php',

        // vendor
        './vendor/rawilk/laravel-breadcrumbs/resources/**/*.php',
        './vendor/rawilk/laravel-form-components/resources/js/*.js',
        './vendor/rawilk/laravel-form-components/src/**/*.php',
        './vendor/rawilk/laravel-form-components/resources/**/*.php',
        './vendor/rawilk/laravel-base/resources/**/*.php',
        './vendor/rawilk/laravel-base/src/**/*.php',
        './vendor/rawilk/laravel-base/resources/js/**/*.js',
        './vendor/rawilk/laravel-base/config/laravel-base.php',
    ],

    safelist: safelist.safelist,

    plugins: [
        require('@tailwindcss/forms'),

        // vendor plugins
        require('../../../vendor/rawilk/laravel-base/resources/js/tailwind-plugins/alert'),
        require('../../../vendor/rawilk/laravel-base/resources/js/tailwind-plugins/badge'),
        require('../../../vendor/rawilk/laravel-base/resources/js/tailwind-plugins/button'),
    ],

    theme: {
        extend: {
            colors: {
                slate: colors.slate,
                gray: colors.gray,
                rose: colors.rose,
                orange: colors.orange,
                indigo: colors.indigo,
                pink: colors.pink,
                yellow: colors.yellow,
                brand: '#0088CC', // If updating, be sure to update `views/vendor/mail/html/layout.blade.php` files logo colors
            },

            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
                serif: ['PT Serif', ...defaultTheme.fontFamily.serif],
            },

            minWidth: {
                '0': '0',
                '1/4': '25%',
                '1/2': '50%',
                '3/4': '75%',
                'full': '100%',
                '48': '12rem',
            },

            cursor: {
                grab: 'grab',
                grabbing: 'grabbing',
                help: 'help',
            },

            transitionTimingFunction: {
                css: 'ease',
            },

            zIndex: {
                top: '9999',
            },
        },
    },
};
