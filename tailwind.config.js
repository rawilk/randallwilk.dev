module.exports = {
    presets: [require('./resources/css/build/tailwind-preset')],

    theme: {
        extend: {
            fontFamily: {
                mono: ['Fira Code', 'Monaco', 'Liberation Mono', 'Courier New', 'monospace'],
            },
        },
    },
};
