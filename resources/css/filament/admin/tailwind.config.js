import preset from '../../../../vendor/filament/filament/tailwind.config.preset';
import randallWilkPreset from '../../build/preset';

export default {
    presets: [randallWilkPreset, preset],

    content: [
        // Filament
        './app/Filament/Plugins/**/*.php',
        './app/Filament/Admin/**/*.php',
        './app/Filament/Infolists/**/*.php',
        './app/Filament/Tables/**/*.php',
        './app/Filament/Plugins/**/*.php',
        './app/Support/Filament/FilamentDefaults.php',

        // View Components
        './app/View/Components/Feedback/**/*.php',
        './resources/views/components/auth/**/*.blade.php',
        './resources/views/components/feedback/**/*.blade.php',
        './resources/views/components/filament/**/*.blade.php',
        './resources/views/components/profile/**/*.blade.php',
        './resources/views/components/text/**/*.blade.php',
        './resources/views/components/layout/error.blade.php',

        // Admin Panel
        './app/Providers/Filament/AdminPanelProvider.php',

        // Livewire
        './app/Livewire/Profile/**/*.php',
        './app/Livewire/Users/**/*.php',

        // Views
        './resources/views/filament/**/*.blade.php',
        './resources/views/layouts/auth/**/*.blade.php',

        // Vendor
        './vendor/filament/**/*.blade.php',

        ...randallWilkPreset.content,
    ],
};
