import randallWilkPreset from '../../build/preset';
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    presets: [randallWilkPreset],

    darkMode: 'class',

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],

    content: [
        // View components
        './resources/views/components/layout/html.blade.php',
        './resources/views/components/layout/front/footer.blade.php',
        './resources/views/components/layout/docs/**/*.blade.php',
        './resources/views/components/docs/**/*.blade.php',

        './app/Providers/NavigationServiceProvider.php',

        // Layouts
        './resources/views/layouts/docs/**/*.blade.php',

        // Views
        './resources/views/front/pages/docs/**/*.blade.php',

        // JavaScript
        './resources/js/docs/**/*.js',

        // Vendor
        './vendor/filament/support/resources/views/components/dropdown/**/*.blade.php',

        ...randallWilkPreset.content,
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['var(--font-family)', ...defaultTheme.fontFamily.sans],
                display: ['var(--font-display-family)', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                'fenced': '#2d3748',
                'fenced-border': '#778195',
            },

            maxWidth: {
                '8xl': '88rem',
            },

            screens: {
                'mobile': {
                    max: '768px',
                },

                'logo-min': {
                    min: '510px',
                },
            },

            fontSize: {
                xs: ['0.75rem', { lineHeight: '1rem' }],
                sm: ['0.875rem', { lineHeight: '1.5rem' }],
                base: ['1rem', { lineHeight: '2rem' }],
                lg: ['1.125rem', { lineHeight: '1.75rem' }],
                xl: ['1.25rem', { lineHeight: '2rem' }],
                '2xl': ['1.5rem', { lineHeight: '2.5rem' }],
                '3xl': ['2rem', { lineHeight: '2.5rem' }],
                '4xl': ['2.5rem', { lineHeight: '3rem' }],
                '5xl': ['3rem', { lineHeight: '3.5rem' }],
                '6xl': ['3.75rem', { lineHeight: '1' }],
                '7xl': ['4.5rem', { lineHeight: '1' }],
                '8xl': ['6rem', { lineHeight: '1' }],
                '9xl': ['8rem', { lineHeight: '1' }],
            },

            zIndex: {
                top: 9999,
            },
        },
    },
};
