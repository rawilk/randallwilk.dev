const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    // content: [
    //     './resources/views/layouts/docs/**/*.php',
    //     './resources/views/components/docs/**/*.php',
    //     './resources/views/front/pages/docs/**/*.php',
    //
    //     // vendor
    //     './vendor/rawilk/laravel-breadcrumbs/resources/**/*.php',
    //     './vendor/rawilk/laravel-base/resources/**/*.php',
    //     './vendor/rawilk/laravel-base/src/**/*.php',
    // ],

    presets: [require('./tailwind-preset')],

    darkMode: 'class',

    plugins: [require('@tailwindcss/typography')],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Lexend', ...defaultTheme.fontFamily.sans],
                mono: ['Fira Code', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                'fenced': '#2d3748',
            },

            maxWidth: {
                '8xl': '88rem',
            },

            fontSize: {
                xs: ['0.75rem', { lineHeight: '1rem' }],
                sm: ['0.875rem', { lineHeight: '1.5rem' }],
                base: ['1rem', { lineHeight: '2rem' }],
                lg: ['1.125rem', { lineHeight: '1.75rem' }],
                xl: ['1.25rem', { lineHeight: '2rem' }],
                '2xl': ['1.5rem', { lineHeight: '2.5rem' }],
                '3xl': ['2rem', { lineHeight: '2.5rem' }],
                '4xl': ['2.5rem', { lineHeight: '3rem' }],
                '5xl': ['3rem', { lineHeight: '3.5rem' }],
                '6xl': ['3.75rem', { lineHeight: '1' }],
                '7xl': ['4.5rem', { lineHeight: '1' }],
                '8xl': ['6rem', { lineHeight: '1' }],
                '9xl': ['8rem', { lineHeight: '1' }],
            },
        },
    },
};
