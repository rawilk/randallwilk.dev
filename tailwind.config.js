const colors = require('tailwindcss/colors');

module.exports = {

    presets: [require('./tailwind-preset')],

    theme: {

        extend: {

            fontFamily: {
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
                'code-bg': colors.trueGray['100'],
                'fenced': '#2d3748',
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
