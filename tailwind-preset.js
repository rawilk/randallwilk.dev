const colors = require('tailwindcss/colors');

module.exports = {

    mode: 'jit',

    purge: {
        content: [
            './app/**/*.php',
            './resources/**/*.html',
            './resources/**/*.js',
            './resources/**/*.php',
            './config/site.php',

            // Vendor
            './vendor/rawilk/laravel-form-components/resources/js/*.js',
            './vendor/rawilk/laravel-breadcrumbs/resources/**/*.php',
            './vendor/rawilk/laravel-form-components/src/**/*.php',
            './vendor/rawilk/laravel-form-components/resources/**/*.php',
        ],
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),

        // custom plugins
        require('./resources/js/tailwind-plugins/alert'),
        require('./resources/js/tailwind-plugins/badge'),
        require('./resources/js/tailwind-plugins/button'),
    ],

    darkMode: false,

    theme: {

        extend: {

            fontFamily: {
                sans: [
                    'Nunito',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    'Segoe UI',
                    'Ubuntu',
                    'Fira Sans',
                    'Droid Sans',
                    'Helvetica Neue',
                    'sans-serif',
                ],
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

            colors: {
                'blue-gray': colors.blueGray,
                'cool-gray': colors.coolGray,
                orange: colors.orange,
            },

            outline: {
                'blue-gray': [`2px dotted ${colors.blueGray['500']}`, '2px'],
            },

        },

    },

};
