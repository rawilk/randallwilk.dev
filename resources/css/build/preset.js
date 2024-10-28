import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './resources/svg/**/*.svg',

        './resources/views/errors/**/*.blade.php',
        './resources/views/components/errors/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: '#0088CC', // If updating, be sure to update `views/vendor/mail/html/layout.blade.php` files logo colors
            },

            fontFamily: {
                mono: ['var(--font-mono-family, Fira Code)', ...defaultTheme.fontFamily.mono],
            },
        },
    },
};
