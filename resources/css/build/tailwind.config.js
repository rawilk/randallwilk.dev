const colors = require('tailwindcss/colors');

module.exports = {
    presets: [require('./tailwind-preset')],

    theme: {
        extend: {
            fontFamily: {
                mono: ['Fira Code', 'Monaco', 'Liberation Mono', 'Courier New', 'monospace'],
            },
        },
    },
};
