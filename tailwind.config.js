const colors = require('tailwindcss/colors');

module.exports = {
    presets: [require('./tailwind-preset')],

    extend: {
        fontFamily: {
            mono: ['Fira Code', 'Monaco', 'Liberation Mono', 'Courier New', 'monospace'],
        },

        fontSize: {
            '6xl': '5rem',
            'doc-nav': '.75em',
            'code': '.75rem',
        },

        colors: {
            'code-bg': colors.slate['100'],
            fenced: '#2d3748',
        },

        lineHeight: {
            code: '1.9',
        },
    },
};
