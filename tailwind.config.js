const colorPalette = require('./tailwind-preset').colorPalette;
const colors = require('tailwindcss/colors');

module.exports = {

    presets: [require('./tailwind-preset')],

    purge: {
        content: [
            './app/**/*.php',
            './resources/**/*.html',
            './resources/**/*.js',
            './resources/**/*.php',
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
                serif: [
                    'Lora',
                    'Constantia',
                    'Lucida Bright',
                    'Lucidabright',
                    'Lucida',
                    'Dejavu Serif',
                    'Bitstream Vera Serif',
                    'Liberation Serif',
                    'Georgia',
                    'serif',
                ],
                mono: ['Fira Code', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
            },

            fontSize: {
                '6xl': '5rem',
                'doc-nav': '.75em',
                'code': '.75rem',
            },

            screens: {
                'print': {'raw': 'print'},
                // => @media print {...}
            },

            colors: {
                black: '#172a3d',
                'code-bg': colors.trueGray['100'],
                'fenced': '#2d3748',

                primary: colorPalette('blue'),
                success: colorPalette('emerald'),
                danger: colorPalette('red'),
                warning: colorPalette('amber'),
                info: colorPalette('cyan'),
                secondary: colorPalette('blueGray'),
            },

            lineHeight: {
                code: '1.9',
            },

            maxWidth: {
                columns: '68rem', // xl + (2 * large gap)
            },

        },

    },

};
