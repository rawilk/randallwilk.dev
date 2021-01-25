const colorPalette = require('./tailwind-preset').colorPalette;

module.exports = {

    presets: [require('./tailwind-preset')],

    purge: {
        content: [
            './app/**/*.php',
            './resources/**/*.html',
            './resources/**/*.js',
            './resources/**/*.php',
            './config/site.php',
            './vendor/rawilk/laravel-form-components/src/**/*.php',
            './vendor/rawilk/laravel-form-components/resources/**/*.php',
        ],

        options: {
            defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
            safelist: [
                /-active$/,
                /-enter$/,
                /-leave-to$/,
                /show$/,
            ],
        },
    },

    theme: {

        screens: {
            'sm': '500px',
            'md': [
                // Sidebar appears at 768px, so revert to `sm:` styles between 768px
                // and 868px, after which the main content area is wide enough again to
                // apply the `md:` styles.
                {'min': '668px', 'max': '767px'},
                {'min': '868px'},
            ],
            'lg': '1100px',
            'xl': '1400px',
        },

        extend: {

            screens: {
                'print': {'raw': 'print'},
                // => @media print {...}
            },

            colors: {
                primary: colorPalette('blue'),
                success: colorPalette('emerald'),
                danger: colorPalette('red'),
                warning: colorPalette('amber'),
                info: colorPalette('cyan'),
                secondary: colorPalette('blueGray'),
            },

        },

    },

};
