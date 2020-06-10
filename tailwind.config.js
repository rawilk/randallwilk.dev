module.exports = {
    theme: {
        fontFamily: {
            display: ['Nunito', 'sans-serif'],
            body: ['Graphik', 'sans-serif'],
        },
    },
    variants: {},
    plugins: [
        require('tailwindcss'),
        require('autoprefixer'),
    ],
    purge: [
        './resources/views/**/*.php',
        './resources/js/**/*.js',
    ]
}
