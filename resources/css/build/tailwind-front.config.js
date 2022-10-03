const colors = require('tailwindcss/colors');

module.exports = {
    presets: [require('./tailwind-preset')],

    plugins: [require('@tailwindcss/typography')],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    'Noto Sans',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    'Calibri',
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
            },

            colors: {
                black: '#172a3d',
            },

            screens: {
                'print': { raw: 'print' },
                // => @media print {...}
            },

            keyframes: {
                scroller: {
                    '0%': { transform: 'translate(0, 0)', opacity: 0 },
                    '40%': { opacity: 1 },
                    '80%': { transform: 'translate(0, 23px)', opacity: 0 },
                    '100%': { opacity: 0 },
                },
            },

            animation: {
                scroller: 'scroller 2s infinite',
            },
        },
    },
};
