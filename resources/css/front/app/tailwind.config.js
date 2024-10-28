import preset from '../../../../vendor/filament/filament/tailwind.config.preset';
import randallWilkPreset from '../../build/preset';
import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';

export default {
    presets: [randallWilkPreset, preset],

    content: [
        // Filament
        './app/Support/Filament/FilamentDefaults.php',

        // View Components
        './resources/views/components/front/**/*.blade.php',
        './resources/views/components/layout/html.blade.php',
        './resources/views/components/layout/front/**/*.blade.php',
        './resources/views/components/images/**/*.blade.php',
        './resources/views/components/logo.blade.php',
        './resources/views/components/back-to-top.blade.php',
        './app/Services/Menus/**/*.php',

        './app/Providers/NavigationServiceProvider.php',

        // Livewire
        './resources/views/livewire/front/**/*.blade.php',

        // Views
        './resources/views/layouts/front/**/*.blade.php',
        './resources/views/front/**/*.blade.php',

        // Vendor
        './vendor/filament/**/*.blade.php',

        ...randallWilkPreset.content,
    ],

    theme: {
        extend: {
            fontFamily: {
                serif: ['var(--font-serif-family)', 'Constantia', 'Lucida Bright', 'Lucidabright', 'Lucida', 'Dejavu Serif', ...defaultTheme.fontFamily.serif],
            },

            fontSize: {
                '6xl': '5rem',
            },

            colors: {
                black: '#172a3d',

                // Not really sure why I needed to do this for gray...
                gray: colors.gray,
            },

            screens: {
                'mobile': {
                    max: '768px',
                },

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
