const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {

    purge: {
        content: [
            './app/**/*.php',
            './resources/**/*.html',
            './resources/**/*.js',
            './resources/**/*.php',
        ],

        options: {
            defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
            safelist: [/-active$/, /-enter$/, /-leave-to$/, /show$/],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],

    darkMode: false, // or 'media' or 'class'

    variants: {
        extend: {
            backgroundColor: ['even', 'odd'],
            boxShadow: ['focus-within'],
        },
    },

    theme: {

        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: '#000',
            white: '#fff',
            'blue-gray': colors.blueGray,
            'cool-gray': colors.coolGray,
            gray: colors.gray,
            blue: colors.blue,
            red: colors.red,
            rose: colors.rose,
            green: colors.green,
            orange: colors.orange,
        },

        extend: {

            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
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

            outline: {
                'blue-gray': [`2px dotted ${colors.blueGray['500']}`, '2px'],
            },

        },

    },

    // Helpers...

    colorPalette: (color, defaultColorLevel = '400') => {
        return {
            ...Object.assign.apply(
                {},
                ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900']
                    .map(level => ({ [level]: colors[color][level] }))
            ),
            DEFAULT: colors[color][defaultColorLevel],
        }
    },

};
