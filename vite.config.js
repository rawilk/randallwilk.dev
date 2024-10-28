import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import babel from 'vite-plugin-babel';

export default defineConfig({
    plugins: [
        babel(),

        laravel({
            input: [
                // Docs
                'resources/css/front/app/app.css',
                'resources/js/docs.js',

                // Front
                'resources/css/front/docs/app.css',
                'resources/js/app.js',

                // Admin
                'resources/js/admin-app.js',

                // Panels
                'resources/css/filament/admin/theme.css',
            ],

            refresh: true,
        }),
    ],
});
