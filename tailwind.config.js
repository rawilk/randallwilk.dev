const colors = require('@tailwindcss/ui/colors');

module.exports = {
    purge: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],

    plugins: [
        require('@tailwindcss/ui'),
    ],

    theme: {
        boxShadow: {
            default: '0 2px 4px 0 rgba(76, 55, 55, 0.12)',
            light: '0 2px 4px 0 rgba(76, 55, 55, 0.04)',
            md: '0 4px 8px 0 rgba(162, 184, 193, 0.12), 0 2px 4px 0 rgba(76, 55, 55, 0.12)',
            lg: '0 15px 30px 0 rgba(162, 184, 193, 0.14), 0 5px 15px 0 rgba(76, 55, 55, 0.12)',
            inner: 'inset 0 2px 4px 0 rgba(76, 55, 55, 0.12)',
            'inner-light': 'inset 0 2px 4px 0 rgba(76, 55, 55, 0.04)',
            none: 'none',
        },

        screens: {
            sm: '720px',
            md: '960px',
            lg: '1230px',
            xl: '1615px',
            print: { raw: 'print' },
        },

        fontFamily: {
            display: ['Nunito', 'sans-serif'],
            body: ['Graphik', 'sans-serif'],
            serif: ['Lora', 'serif'],
            mono: ['Fira Code', 'monospace'],
        },

        lineHeight: {
            none: 1,
            tight: 1.1,
            normal: 1.6,
            loose: 2,
        },

        letterSpacing: {
            tight: '-0.05em',
            normal: '0',
            wide: '0.05em',
        },

        extend: {
            colors: {
                black: '#172a3d',

                'gold-lightest': '#eee8d6',
                'gold-darkest': '#51492c',

                'gray-lightest': '#f3efea',
                'gray-lighter': '#cbd2ce',
                'gray-light': '#b8bfbb',
                'gray-dark': '#686666',
                'gray-darker': '#4c534f',
                'gray-darkest': '#171e1a',

                'blue-lightest': '#cae1e8',
                'blue-lighter': '#22a4c9',
                'blue-light': '#22a4c9',
                'blue-dark': '#004966',
                'blue-darker': '#172a3d',
                'blue-darkest': '#0f1c29',

                'pink-lightest': '#f2d8db',
                pink: '#dd9099',
                'pink-dark': '#8d3b44',

                'green-lightest': '#daefe8',
                'green-lighter': '#94dac4',
                'green-light': '#57c9a5',
                green: '#21b989',
                'green-dark': '#0a8867',

                purple: '#885de3',
            },

            fontSize: {
                xxs: '.55rem',
                '6xl': '5rem',
            },

            width: {
                '2px': '2px',
            },

            height: {
                '2px': '2px',
                '18': '4.5rem',
                '1/2': '50%',
            },

            maxWidth: {
                sm: '25rem', // xl / 2 - half gap
                md: '40rem',
                lg: '50rem',
                xl: '60rem',
                '2xl': '70rem',
                '3xl': '80rem',
                '4xl': '90rem',
                '5xl': '100rem',
                '1/2': '50vw',
                columns: '68rem', // xl + (2 * large gap)
            },

            maxHeight: {
                none: 'none',
                '16': '4rem',
                '24': '6rem',
            },

            minHeight: {
                '10': '2.5rem',
            },

            zIndex: {
                auto: 'auto',
                back: -1,
            },
        },
    },
};
