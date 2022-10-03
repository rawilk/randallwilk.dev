module.exports = {
    plugins: [
        require('tailwindcss/nesting'),
        require('postcss-multiple-tailwind')({
            defaultConfig: 'build/tailwind.config.js',
        }),
    ],
};
